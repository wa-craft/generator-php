#!/usr/bin/env php
<?php
/**
 * thinkphp v5 脚手架创建工具
 */
//定义基本数据
define('TAR_ROOT_PATH', './deploy');
define('DBFILE_PATH', TAR_ROOT_PATH . '../../database/');
define('TMPL_PATH', './template');
define('PUB_PATH', TAR_ROOT_PATH . '/public');
define('SHARE_PATH', './share');
define('VERSION', '0.5.0');

$config = require './config.php';

$build_actions = $config['build_actions'];

if (!is_dir(TAR_ROOT_PATH)) mkdir(TAR_ROOT_PATH, 0600);
$applications = require "./project/applications.php";

foreach ($applications as $application) {
    //创建目录
    $_app_path = TAR_ROOT_PATH . '/' . $application['name'];
    echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
    if (!is_dir($_app_path)) mkdir($_app_path, 0600);

    //装载模板文件
    echo "INFO: reading templates ..." . PHP_EOL;
    $templates = $config['templates'];

    //装载默认设置
    $defaults = $config['defaults'];

    //创建入口文件
    if ($build_actions['portal']) {
        $_portal = (isset($application['portal'])) ? $application['portal'] : $application['name'];
        write_template_file(PUB_PATH, ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['name_space'], $templates);
    }

    if ($build_actions['copy']) {
        //拷贝应用文件
        copy_files(SHARE_PATH . '/php', $_app_path);
    }

    $modules = $application['modules'];
    foreach ($modules as $module) {
        //创建模块目录
        $_module_path = $_app_path . '/' . $module['name'];
        if (!is_dir($_module_path)) {
            mkdir($_module_path, 0600);
            echo "INFO: creating module directory: {$_module_path} ..." . PHP_EOL;
        }

        //生成代码

        //生成单独控制器代码
        $_controller_path = $_module_path . '/controller';
        if (!is_dir($_controller_path)) mkdir($_controller_path, 0600);
        $_view_path = $_module_path . '/view';
        if (!is_dir($_view_path)) mkdir($_view_path, 0600);

        if (isset($module['controllers'])) $controllers = $module['controllers'];
        else $controllers = [];
        foreach ($controllers as $controller) {
            write_template_file($_controller_path, $module, ['name' => 'controller'], $controller, $application['name_space'], $templates);
            //生成VIEW模板
            if ($build_actions['view']) {
                if (!isset($controller['actions'])) $controller['actions'] = $defaults['actions'];

                //生成方法的前台页面
                $_view_model_path = $_view_path . '/' . strtolower($controller['name']);
                foreach ($controller['actions'] as $action) {
                    write_template_file($_view_model_path, $module, $action, $controller, $application['name_space'], $templates, '.html');
                }
            }
        }

        //根据模块生成代码
        $models = $module['models'];
        foreach ($models as $model) {
            if ($build_actions['controller']) {
                //生成CRUD控制器
                write_template_file($_controller_path, $module, ['name' => 'controller'], $model, $application['name_space'], $templates);
            }

            if ($build_actions['model']) {
                //生成模型
                $_model_path = $_module_path . '/model';
                write_template_file($_model_path, ['name' => 'common'], ['name' => 'model'], $model, $application['name_space'], $templates);
            }

            if ($build_actions['validate']) {
                //生成校验器
                $_validate_path = $_module_path . '/validate';
                write_template_file($_validate_path, ['name' => 'common'], ['name' => 'validate'], $model, $application['name_space'], $templates);
            }

            //生成VIEW模板
            if ($build_actions['view']) {
                if (!isset($model['actions'])) $model['actions'] = $defaults['actions'];

                //生成方法的前台页面
                $_view_model_path = $_view_path . '/' . strtolower($model['name']);
                foreach ($model['actions'] as $action) {
                    write_template_file($_view_model_path, $module, $action, $model, $application['name_space'], $templates, '.html');
                }
            }
            //生成 sql
            if ($build_actions['sql']) {
                write_template_file(DBFILE_PATH, $module, ['name' => 'sql_table'], $model, $application['name_space'], $templates, '.sql');
            }
        }
        if ($build_actions['copy']) {
            //拷贝基础文件
            copy_files(SHARE_PATH . '/html/layout', $_view_path . '/layout');
        }
    }

}

echo "ThinkForge Builder, Version: " . VERSION . PHP_EOL;


//定义基本函数
/**
 * 扫描某个路径下的所有文件，并拷贝到目标路径下
 * @param $src_path
 * @param $tar_path
 */
function copy_files($src_path, $tar_path)
{
    if (!is_dir($tar_path)) mkdir($tar_path, 0600);

    $files = scandir($src_path);
    foreach ($files as $file) {
        $src_file_name = $src_path . '/' . $file;
        $tar_file_name = $tar_path . '/' . $file;
        if (is_file($src_file_name)) {
            copy($src_file_name, $tar_file_name);
            echo "INFO: copying " . $file . "\n";
        }
    }
}

/**
 * 处理模板并写入模板文件的函数
 * @param string $path 模板目标目录
 * @param array $module 目标
 * @param array $index 模板索引
 * @param array $model 模型
 * @param string $name_space 命名空间
 * @param array $templates 模板数组
 * @param string $suffix 文件后缀名
 */
function write_template_file($path, $module, $index, $model, $name_space, $templates, $suffix = '.php')
{
    if (!is_dir($path)) {
        mkdir($path, 0600);
        //echo "INFO: creating {$index['name']} directory: {$path} ..." . PHP_EOL;
    }

    switch ($suffix) {
        case '.php':
            write_php($path, $module, $index, $model, $name_space, $templates);
            break;
        case '.sql':
            write_sql($path, $index, $model, $name_space, $templates);
            break;
        case '.html':
            write_html($path, $module, $index, $model, $templates);
            break;
    }
}

/**
 * 生成 html 代码
 * @param $path
 * @param $module
 * @param $index
 * @param $model
 * @param $templates
 */
function write_html($path, $module, $index, $model, $templates)
{
    global $defaults;
    if (!is_dir($path)) {
        mkdir($path, 0600);
        //echo "INFO: creating {$index['name']} directory: {$path} ..." . PHP_EOL;
    }

    $content = str_replace('{{MODEL_NAME}}', strtolower($model['name']), $templates['view_' . $index['name']]);
    $content = isset($module['comment']) ? str_replace('{{MODULE_COMMENT}}', $module['comment'], $content) : $content;
    $content = str_replace('{{MODEL_COMMENT}}', $model['comment'], $content);
    $content = str_replace('{{ACTION_COMMENT}}', $index['comment'], $content);

    //处理模型的字段
    if (isset($model['fields'])) {
        $fields = $model['fields'];
        if ($index['name'] == 'index') {
            $_tr = '<th>ID</th>' . "\n";
            $_td = '<td>{$it.id}</td>' . "\n";
            foreach ($fields as $field) {
                $_tr .= '<th>' . $field['title'] . '</th>' . "\n";
                $_td .= '<td>{$it.' . $field['name'] . '}</td>' . "\n";
            }
            $content = str_replace('{{TR_LOOP}}', $_tr, $content);
            $content = str_replace('{{TD_LOOP}}', $_td, $content);
        } else {
            foreach ($fields as $field) {
                $content_field = $templates['view_' . $index['name'] . '_field'];
                $content_field = str_replace('{{FORM_FIELD}}', getFieldHTML($field, $index['name']), $content_field);
                $content_field = str_replace('{{FIELD_NAME}}', $field['name'], $content_field);
                $content_field = str_replace('{{FIELD_TITLE}}', $field['title'], $content_field);
                if (isset($field['rule'])) $_comment = '请输入' . $field['title'] . '，必须是' . $defaults['rules'][$field['rule']];
                else $_comment = '';
                $content_field = str_replace('{{FIELD_COMMENT}}', $_comment, $content_field);

                if (isset($field['required'])) $_is_required = ($field['required']) ? '（* 必须）' : '';
                else $_is_required = '';
                $content_field = str_replace('{{IS_REQUIRED}}', $_is_required, $content_field);

                $content = str_replace('{{FIELD_LOOP}}', $content_field . "\n{{FIELD_LOOP}}", $content);
            }
            $content = str_replace('{{FIELD_LOOP}}', '', $content);
        }
    }
    $_file = $path . '/' . strtolower($index['name']) . '.html';

    file_put_contents($_file, $content);
    echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
}

/**
 * 生成 sql 代码
 * @param $path
 * @param $index
 * @param $model
 * @param $name_space
 * @param $templates
 */
function write_sql($path, $index, $model, $name_space, $templates)
{
    if (!is_dir($path)) {
        mkdir($path, 0600);
        //echo "INFO: creating {$index['name']} directory: {$path} ..." . PHP_EOL;
    }

    $_class_name = $model['name'];
    $content = str_replace('{{APP_NAME}}', $name_space, $templates[$index['name']]);
    $content = isset($model['name']) ? str_replace('{{MODEL_NAME}}', strtolower($model['name']), $content) : $content;
    $content = isset($model['comment']) ? str_replace('{{MODEL_COMMENT}}', $model['comment'], $content) : $content;

    //处理SQL的字段
    $content_field = '';
    if (isset($model['fields'])) {
        $fields = $model['fields'];
        foreach ($fields as $field) {
            $content_field = '  ' . getFieldSQL($field);
            $content_field = str_replace('{{FIELD_NAME}}', $field['name'], $content_field);
            $content_field = str_replace('{{FIELD_TITLE}}', $field['title'], $content_field);
            $content = str_replace('{{FIELD_LOOP}}', $content_field . "\n{{FIELD_LOOP}}", $content);
        }
        $content = str_replace('{{FIELD_LOOP}}', '', $content);
    }
    $content = str_replace('{{CONTROLLER_PARAMS}}', $content_field, $content);
    $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);

    $_file = $path . '/' . strtolower($model['name']) . '.sql';

    file_put_contents($_file, $content);
    echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
}

/**
 * 生成 php 代码
 * @param $path
 * @param $module
 * @param $index
 * @param $model
 * @param $name_space
 * @param $templates
 */
function write_php($path, $module, $index, $model, $name_space, $templates)
{
    global $defaults;
    if (!is_dir($path)) {
        mkdir($path, 0600);
        //echo "INFO: creating {$index['name']} directory: {$path} ..." . PHP_EOL;
    }

    $_name_space = $name_space . '\\' . $module['name'] . '\\' . $index['name'];
    $_class_name = $model['name'];
    $content = str_replace('{{NAME_SPACE}}', $_name_space, $templates[$index['name']]);
    $content = isset($module['comment']) ? str_replace('{{MODULE_COMMENT}}', $module['comment'], $content) : $content;
    $content = isset($model['name']) ? str_replace('{{APP_NAME}}', $model['name'], $content) : $content;
    $content = isset($model['name']) ? str_replace('{{MODEL_NAME}}', $model['name'], $content) : $content;
    $content = isset($model['comment']) ? str_replace('{{MODEL_COMMENT}}', $model['comment'], $content) : $content;

    //处理与控制器相关的模板
    if ($index['name'] == 'controller') {
        //处理控制器的方法
        if (isset($model['actions'])) {
            $actions = $model['actions'];
            foreach ($actions as $action) {
                $content_action = $templates['controller_action'];
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['comment'], $content_action);

                $content = str_replace('{{CONTROLLER_ACTIONS}}', $content_action . "\n{{CONTROLLER_ACTIONS}}", $content);
            }
        }
        $content = str_replace('{{CONTROLLER_ACTIONS}}', '', $content);

        //处理控制器的参数
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= '$model->' . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $content = str_replace('{{CONTROLLER_PARAMS}}', $content_field, $content);
    }

    //处理校验器相关的模板
    if ($index['name'] == 'validate') {
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= PHP_EOL . "\t\t['" . $field['name'] . "', '";
                $content_field .= $field['required'] ? 'require|' : '';
                $content_field .= $field['rule'] . '\',\'';
                $content_field .= $field['required'] ? '必须输入：' . $field['title'] . '|' : '';
                $content_field .= $defaults['rules'][$field['rule']];
                $content_field .= '\'],';
            }
            $content = str_replace('{{VALIDATE_FIELDS}}', $content_field . "\n{{VALIDATE_FIELDS}}", $content);
        }
        $content = str_replace(",\n{{VALIDATE_FIELDS}}", "\n\t", $content);
    }

    $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
    $_file = $path . '/' . $model['name'] . '.php';

    file_put_contents($_file, $content);
    echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
}

/**
 * 判断字段的类型和参数，来生成不同类型的参数字段html
 * @param $field
 * @param string $action
 * @return string
 */
function getFieldHTML($field, $action = 'add')
{
    if ($field['rule'] == 'boolean') {
        if ($action == 'add') return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
        else  return "<input type=\"checkbox\" class=\"md-check\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
    }
    if (preg_match('/_id$/', $field['name'])) {
        $_model = str_replace('_id', '', $field['name']);
        if ($action == 'add') return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.name}</option>{/volist}</select> ";
        else return "<select class=\"form-control edited\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">{volist name=\"" . $_model . "List\" id=\"it\"}<option value=\"{\$it.id}\">{\$it.name}</option>{/volist}</select> ";
    }
    if ($action == 'add') return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\">";
    else  return "<input type=\"text\" class=\"form-control\" id=\"{{FIELD_NAME}}\" name=\"{{FIELD_NAME}}\" value=\"{\$it.{{FIELD_NAME}}}\">";
}

/**
 * 判断字段的类型和参数，生成不同类型的字段 sq
 * @param $field
 * @return string
 */
function getFieldSQL($field)
{
    if (preg_match('/_id$/', $field['name'])) {
        return '`{{FIELD_NAME}}` bigint(20) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
    }
    if (preg_match('/^is_/', $field['name'])) {
        return '`{{FIELD_NAME}}` tinyint(1) NOT NULL COMMENT \'{{FIELD_TITLE}}\',,';
    }

    return '`{{FIELD_NAME}}` varchar(100) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
}
