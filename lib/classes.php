<?php

/**
 * Class Builder 构建程序
 */
class Builder
{
    //缓存
    protected $cache = [];
    //配置参数
    protected $config = [];
    //数据
    protected $data = null;
    //生成代码的目标路径
    protected $target_path = '';
    //默认值
    public static $defaults = [];
    //创建设置
    protected $actions = [
        //是否生成入口文件
        'portal' => true,
        //是否生成控制器程序
        'controller' => true,
        //是否生成模型程序
        'model' => true,
        //是否生成校验器程序
        'validate' => true,
        //是否生成视图模板文件
        'view' => true,
        //是否生成SQL数据库文件
        'sql' => true,
        //是否复制其他静态资源
        'copy' => true,
        //是否根据应用 portal 生成 nginx 配置文件，谨慎使用
        'nginx' => true,
        //是否根据应用 portal 生成 .htaccess，谨慎使用
        'apache' => true,
        //是否生成目录数组
        'menu' => true
    ];
    //版本
    protected $version = '1.1.0';

    /**
     * 通过数组设置项目配置信息
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->config = $config;
    }

    /**
     * 通过指定的文件名获取并设置项目配置信息
     * @param $file
     */
    public function setConfigFromFile($file)
    {
        $this->config = require($file);
    }

    /**
     * 设置数据
     * @param array $data
     */
    public function setData($data = [])
    {
        $this->data = $data;
    }

    /**
     * 通过文件读取并设置数据
     * @param $file
     */
    public function setDataFromFile($file)
    {
        $this->data = require($file);
    }

    /**
     * 从命令行获取的动作参数来生成动作表
     * @param string $action
     */
    public function setActions($action = 'all')
    {
        switch ($action) {
            case 'mvc':
                foreach ($this->actions as $key => $action) {
                    if (in_array($key, ['model', 'controller', 'view', 'validate'])) {
                        $action = true;
                    } else {
                        $action = false;
                    }
                }
                break;
            case 'copy':
                foreach ($this->actions as $key => $action) {
                    if (in_array($key, ['copy'])) {
                        $action = true;
                    } else {
                        $action = false;
                    }
                }
                break;
            case 'all':
            default:
                foreach ($this->actions as &$action) {
                    $action = true;
                }
        }
    }

    public function setTargetPath($path)
    {
        $this->target_path = $path;
    }

    /**
     * 创建项目文件的主方法
     */
    public function build()
    {
        $build_actions = $this->actions;

        //使用 git clone 创建初始目录结构
        $cmd = 'git clone ' . ROOT_REPOS . ' ' . $this->target_path . ' && ' . 'rm -rf ' . $this->target_path . '/.git';
        shell_exec($cmd);

        //创建基本目录
        $application_path = $this->target_path . '/' . APP_PATH;
        $database_path = $this->target_path . '/' . DBFILE_PATH;
        $profile_path = $this->target_path . '/' . PROFILE_PATH;
        $public_path = $this->target_path . '/' . PUB_PATH;

        mk_dir($this->target_path);
        mk_dir($application_path);
        mk_dir($database_path);
        mk_dir($profile_path);
        mk_dir($public_path);

        //装载模板文件
        echo "INFO: reading templates ..." . PHP_EOL;
        $templates = $this->config['templates'];

        //装载默认设置
        Builder::$defaults = $this->config['defaults'];
        $defaults = Builder::$defaults;

        $project = $this->data;

        $applications = $project['applications'];

        //生成 nginx 配置文件
        if ($build_actions['nginx']) {
            write_nginx($profile_path, $templates['nginx'], $project['domain'], $applications);
        }

        //生成 apache htaccess 配置文件
        if ($build_actions['apache']) {
            write_apache($public_path, $templates['apache'], $applications);
        }

        foreach ($applications as $application) {
            //创建目录
            $_app_path = $this->target_path . '/' . APP_PATH . '/' . $application['name'];
            echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
            mk_dir($_app_path);

            //创建入口文件
            if ($build_actions['portal']) {
                $_portal = (isset($application['portal'])) ? $application['portal'] : $application['name'];
                write_php($public_path, ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['namespace'], $templates);
            }

            if ($build_actions['copy']) {
                //拷贝应用文件
                copy_files(SHARE_PATH . '/application', $_app_path);
            }

            //写入 config / database 配置文件
            write_config($_app_path, 'config', $templates, ['NAMESPACE' => $application['namespace']]);
            write_config($_app_path, 'database', $templates, ['PROJECT_NAME' => $project['name'], 'APP_NAME' => $application['name']]);

            $modules = $application['modules'];
            foreach ($modules as $module) {
                //创建模块目录
                $_module_path = $_app_path . '/' . $module['name'];
                mk_dir($_module_path);

                //生成代码

                //生成单独控制器代码
                $_controller_path = $_module_path . '/controller';
                mk_dir($_controller_path);
                $_view_path = $_module_path . '/view';
                mk_dir($_view_path);

                if (isset($module['controllers'])) $controllers = $module['controllers'];
                else $controllers = [];
                foreach ($controllers as $controller) {
                    Controller::writeToFile($_controller_path, $module, ['name' => 'controller'], $controller, $application['namespace'], $templates);
                    //生成VIEW模板
                    if ($build_actions['view']) {
                        if (!isset($controller['actions'])) $controller['actions'] = $defaults['actions'];

                        //生成方法的前台页面
                        $_view_model_path = $_view_path . '/' . strtolower($controller['name']);
                        foreach ($controller['actions'] as $action) {
                            View::writeToFile($_view_model_path, $module, $action, $controller, $templates);
                        }
                    }
                }

                //生成 traits 的代码
                if (isset($module['traits'])) {
                    $_traits_path = $_module_path . '/traits';
                    mk_dir($_traits_path);
                    $traits = $module['traits'];
                    foreach ($traits as $trait) {
                        Traits::writeToFile($_traits_path, $module, ['name' => 'traits'], $trait, $application['namespace'], $templates);
                    }
                }


                //根据模块生成代码
                $models = $module['models'];
                foreach ($models as $model) {
                    //判断是否存在 autoWriteTimeStamp 参数
                    if (isset($model['autoWriteTimeStamp'])) {
                        if ($model['autoWriteTimeStamp']) {
                            $model['fields'] = array_merge($model['fields'], $defaults['autoTimeStampFields']);
                        }
                    }

                    if ($build_actions['controller']) {
                        //生成CRUD控制器
                        Controller::writeToFile($_controller_path, $module, ['name' => 'controller'], $model, $application['namespace'], $templates);
                    }

                    if ($build_actions['model']) {
                        //生成模型
                        $_model_path = $_module_path . '/model';
                        Model::writeToFile($_model_path, $module, ['name' => 'model'], $model, $application['namespace'], $templates);
                    }

                    if ($build_actions['validate']) {
                        //生成校验器
                        $_validate_path = $_module_path . '/validate';
                        Validate::writeToFile($_validate_path, ['name' => 'common'], ['name' => 'validate'], $model, $application['namespace'], $templates);
                    }

                    //生成VIEW模板
                    if ($build_actions['view']) {
                        if (!isset($model['actions'])) $model['actions'] = $defaults['actions'];

                        //生成方法的前台页面
                        $_view_model_path = $_view_path . '/' . strtolower($model['name']);
                        foreach ($model['actions'] as $action) {
                            View::writeToFile($_view_model_path, $module, $action, $model, $templates);
                        }
                    }
                    //生成 sql
                    if ($build_actions['sql']) {
                        write_sql($database_path, ['name' => 'sql_table'], $model, $application['namespace'], $templates);
                    }
                }
                if ($build_actions['copy']) {
                    //拷贝基础文件
                    copy_files(SHARE_PATH . '/html/layout', $_view_path . '/layout');
                }
            }
        }

        echo "ThinkForge Builder, Version: " . $this->version . PHP_EOL;
    }
}

class Node
{
    static public $types = [
        'PROJECT',
        'APPLICATION',
        'MODULE',
        'CONTROLLER',
        'MODEL',
        'VIEW',
        'ACTION',
        'FIELD'
    ];
    //节点类型，来自于 $types
    protected $type = 0;
    //节点名称，英文小写
    protected $name = '';
    //节点说明，中文
    protected $caption = '';

    /**
     * 根据参数动态匹配赋值类的属性
     * @param array $params
     */
    public function init($params = [])
    {
        foreach ($params as $key => $param) {
            if (property_exists($this, $key)) {

            }
        }
    }

    /**
     * 进行处理的主函数
     */
    public function process()
    {
        //创建目录
        //拷贝文件
        //处理模板文件
        //写入文件
    }

    /**
     * 根据节点属性创建相关目录
     */
    protected function makeDir()
    {

    }

    /**
     * 根据节点属性拷贝相关文件
     * @param array $files
     */
    protected function copyFiles($files = [])
    {

    }

    /**
     * 根据节点属性生成HTML内容
     */
    protected function generateHTML()
    {

    }

    /**
     * 根据节点属性生成SQL内容
     */
    protected function generateSQL()
    {

    }

    /**
     * 根据节点属性生成PHP内容
     */
    protected function generatePHP()
    {

    }

    /**
     * 根据节点属性生成其他内容
     */
    protected function generateMISC()
    {

    }
}

class NodeFactory
{
    public static function createNode()
    {

    }
}

class Project extends Node
{
    //项目使用的域名
    protected $domain = '';
    //项目下的应用
    protected $applications = [];
}

class Application extends Node
{
    //应用的根命名空间
    protected $namespace = '';
    //入口文件名称，不需要输入 .php 后缀
    protected $portal = '';
    //应用下的模块
    protected $modules = [];
}

class Module extends Node
{
    //模块下的控制器
    protected $controllers = [];
    //模块下的模型
    protected $models = [];
    //模块下的校验器
    protected $validates = [];
    //模块下的视图
    protected $views = [];
    //默认模块下所有控制器的父控制器名称，会根据此名称自动生成默认控制器，并且模块下所有控制器继承自此控制器
    protected $default_controller = '';
}

class Controller extends Node
{
    //控制器下的方法
    protected $actions = [];
    //控制器默认的父控制器，如果设置此参数，则会忽略模块统一的默认父控制器设置
    protected $parent_controller = '';

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        $defaults = Builder::$defaults;
        mk_dir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $_class_name = $model['name'];
        $content = str_replace('{{NAME_SPACE}}', $_namespace, $templates[$index['name']]);
        $content = isset($module['comment']) ? str_replace('{{MODULE_COMMENT}}', $module['comment'], $content) : $content;
        $content = isset($model['name']) ? str_replace('{{APP_NAME}}', $model['name'], $content) : $content;
        $content = isset($model['name']) ? str_replace('{{MODEL_NAME}}', $model['name'], $content) : $content;
        $content = isset($model['comment']) ? str_replace('{{MODEL_COMMENT}}', $model['comment'], $content) : $content;

        $extend_controller = (isset($model['parent_controller'])) ? $model['parent_controller'] : ((isset($module['default_controller']) ? $module['default_controller'] : $defaults['controller']));
        $content = str_replace('{{DEFAULT_CONTROLLER}}', $extend_controller, $content);
        $content = str_replace('{{APP_PATH}}', APP_PATH, $content);

        //处理与控制器相关的模板
        //处理控制器的方法
        if (isset($model['actions'])) {
            $actions = $model['actions'];
            foreach ($actions as $action) {
                $content_action = $templates['controller_action'];
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['comment'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{CONTROLLER_ACTIONS}}', $content_action . "\n{{CONTROLLER_ACTIONS}}", $content);
            }
        }
        $content = str_replace("{{CONTROLLER_ACTIONS}}", '', $content);

        //处理控制器的参数
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $content = str_replace('{{CONTROLLER_PARAMS}}', $content_field, $content);

        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}

class Traits extends Node
{
    //特征下的方法
    protected $actions = [];

    public static function writeToFile($path, $module, $index, $traits, $namespace, $templates)
    {
        mk_dir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $_class_name = $traits['name'];
        $content = str_replace('{{NAME_SPACE}}', $_namespace, $templates[$index['name']]);

        //处理特征的方法
        if (isset($traits['actions'])) {
            $actions = $traits['actions'];
            foreach ($actions as $action) {
                $content_action = $templates['traits_action'];
                $content_action = str_replace('{{ACTION_NAME}}', $action['name'], $content_action);
                $content_action = str_replace('{{ACTION_COMMENT}}', $action['comment'], $content_action);
                if (array_key_exists('params', $action)) $content_action = str_replace('{{ACTION_PARAMS}}', $action['params'], $content_action);
                else  $content_action = str_replace('{{ACTION_PARAMS}}', '', $content_action);

                $content = str_replace('{{TRAITS_ACTIONS}}', $content_action . "\n{{TRAITS_ACTIONS}}", $content);
            }
        }
        $content = str_replace("{{TRAITS_ACTIONS}}", '', $content);

        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $_file = $path . '/' . $traits['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}

class Model extends Node
{
    //模型下的字段
    protected $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    protected $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    protected $relations = [];

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        mk_dir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $tags = [
            'NAME_SPACE' => $_namespace,
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $model['name']
        ];

        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($module['comment'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) $tags['MODEL_COMMENT'] = $model['comment'];

        $content = $content_relation = parseTemplateTags($tags, $templates[$index['name']]);

        //生成 relations
        if (isset($model['relations'])) {
            $relations = $model['relations'];
            foreach ($relations as $relation) {
                $content_relation = parseTemplateTags(
                    [
                        'RELATION_NAME' => $relation['name'],
                        'RELATION_TYPE' => $relation['type'],
                        'RELATION_MODEL' => $relation['model'],
                        'RELATION_THIS_KEY' => $relation['this_key'],
                        'RELATION_THAT_KEY' => $relation['that_key']
                    ],
                    $templates['model_relation']
                );
                $content = str_replace('{{RELATIONS}}', $content_relation . "\n{{RELATIONS}}", $content);
            }
            $content = str_replace("\n{{RELATIONS}}", '', $content);
        }

        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}

class Validate extends Node
{
    //校验器下的规则
    protected $rules = [];

    public static function writeToFile($path, $module, $index, $model, $namespace, $templates)
    {
        $defaults = Builder::$defaults;
        mk_dir($path);

        $_namespace = $namespace . '\\' . $module['name'] . '\\' . $index['name'];
        $_class_name = $model['name'];
        $tags = [
            'NAME_SPACE' => $_namespace,
            'APP_PATH' => APP_PATH
        ];
        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($module['comment'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) $tags['MODEL_COMMENT'] = $model['comment'];
        $content = $content_relation = parseTemplateTags($tags, $templates[$index['name']]);

        //处理校验器相关的模板
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

        $content = str_replace('{{CLASS_NAME}}', $_class_name, $content);
        $_file = $path . '/' . $model['name'] . '.php';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}

class View extends Node
{
    //视图使用的布局
    protected $layout = '';

    public static function writeToFile($path, $module, $index, $model, $templates)
    {
        $defaults = Builder::$defaults;
        mk_dir($path);

        //判断是否是独立控制器
        $content = str_replace('{{MODEL_NAME}}', strtolower($model['name']), $templates['view_' . $index['name']]);
        $content = str_replace('{{MODULE_NAME}}', strtolower($module['name']), $content);
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
                    if (isset($field['rule'])) {
                        //判断是否是需要生成选择列表的外键
                        if (preg_match('/_id$/', $field['name'])) {
                            $_comment = '请选择';
                        } else {
                            $_comment = '请输入';
                        }
                        $_comment .= $field['title'] . '，必须是' . $defaults['rules'][$field['rule']];
                    } else {
                        $_comment = '';
                    }

                    if (isset($field['required'])) $_is_required = ($field['required']) ? '（* 必须）' : '';
                    else $_is_required = '';

                    $tags_field = [
                        'FORM_FIELD' => getFieldHTML($field, $index['name']),
                        'FIELD_NAME' => $field['name'],
                        'FIELD_TITLE' => $field['title'],
                        'FIELD_COMMENT' => $_comment,
                        'IS_REQUIRED' => $_is_required
                    ];

                    $content = str_replace('{{FIELD_LOOP}}', parseTemplateTags($tags_field, $templates['view_' . $index['name'] . '_field']) . "\n{{FIELD_LOOP}}", $content);
                }
                $content = str_replace("\n{{FIELD_LOOP}}", '', $content);
            }
        }
        $_file = $path . '/' . strtolower($index['name']) . '.html';

        file_put_contents($_file, $content);
        echo "INFO: writing {$index['name']}: {$_file} ..." . PHP_EOL;
    }
}

class Field extends Node
{
    //字段的校验规则
    protected $rule = '';
    //字段是否必须
    protected $is_required = false;
    //字段值必须唯一
    protected $is_unique = false;
}

class Action extends Node
{
    protected $params = [];
}

class Relations
{
    static protected $relation_types = [
        'hasOne',
        'hasMany',
        'belongsTo',
        'belongsToMany'
    ];
    protected $type = 0;
    protected $model = '';
    protected $this_key = '';
    protected $that_key = '';
}
