<?php

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
     * 根据类型与参数创建Node实例的工厂方法
     * @param int $type
     * @param array $params
     * @return Node
     */
    public static function getInstance($type = 0, $params = [])
    {
        $instance = null;
        $type = (count(self::$types) < $type) ? $type : 0;
        $class_name = ucfirst(strtolower(self::$types[$type]));
        if (class_exists($class_name)) {
            $instance = new $class_name();
            if (count($params) !== 0) {
                $instance->init($params);
            }
        }
        return $instance;
    }

    /**
     * 根据参数动态匹配赋值类的属性
     * @param array $params
     */
    public function init($params = [])
    {
        foreach ($params as $key => $param) {
            if (property_exists($this, $key)) {
                $this->$key = $param;
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
        FileHelper::mkdir($path);

        $tags = [
            'NAME_SPACE' => $namespace . '\\' . $module['name'] . '\\' . $index['name'],
            'APP_PATH' => APP_PATH,
            'CLASS_NAME' => $model['name']
        ];

        if (isset($module['comment'])) $tags['MODULE_COMMENT'] = $module['comment'];
        if (isset($model['name'])) {
            $tags['APP_NAME'] = $model['name'];
            $tags['MODEL_NAME'] = $model['name'];
        }
        if (isset($model['comment'])) {
            $tags['MODEL_COMMENT'] = $model['comment'];
        }

        //为控制器写入父控制器
        $extend_controller = (function ($module, $model, $defaults) {
            $controller = '';
            if (isset($model['parent_controller'])) {
                if ($model['parent_controller'] != '') {
                    $controller = $model['parent_controller'];
                }
            } else if (isset($module['default_controller'])) {
                if ($module['default_controller'] != '') {
                    $controller = $module['default_controller'];
                }
            }
            if ($controller == '') $controller = $defaults['controller'];
            return $controller;
        })($module, $model, $defaults);

        $tags['DEFAULT_CONTROLLER'] = $extend_controller;
        $content = TemplateHelper::parseTemplateTags($tags, $templates[$index['name']]);

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
        $tags['CONTROLLER_ACTIONS'] = '';

        //处理控制器的参数
        $content_field = '';
        if (isset($model['fields'])) {
            $fields = $model['fields'];
            foreach ($fields as $field) {
                $content_field .= "\t\t\$model->" . $field['name'] . " = input('" . $field['name'] . "');\n";
            }
        }
        $tags['CONTROLLER_PARAMS'] = $content_field;

        $content = TemplateHelper::parseTemplateTags($tags, $templates[$index['name']]);

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
        FileHelper::mkdir($path);

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
        FileHelper::mkdir($path);

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

        $content = $content_relation = TemplateHelper::parseTemplateTags($tags, $templates[$index['name']]);

        //生成 relations
        if (isset($model['relations'])) {
            $relations = $model['relations'];
            foreach ($relations as $relation) {
                $content_relation = TemplateHelper::parseTemplateTags(
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
        FileHelper::mkdir($path);

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
        $content = TemplateHelper::parseTemplateTags($tags, $templates[$index['name']]);

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
        FileHelper::mkdir($path);

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
                        'FORM_FIELD' => TemplateHelper::getFieldHTML($field, $index['name']),
                        'FIELD_NAME' => $field['name'],
                        'FIELD_TITLE' => $field['title'],
                        'FIELD_COMMENT' => $_comment,
                        'IS_REQUIRED' => $_is_required
                    ];

                    $content = str_replace('{{FIELD_LOOP}}', TemplateHelper::parseTemplateTags($tags_field, $templates['view_' . $index['name'] . '_field']) . "\n{{FIELD_LOOP}}", $content);
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

    /**
     *
     * @param $field
     * @return string
     */
    public static function makeSQL($field)
    {
        if (preg_match('/_id$/', $field['name'])) {
            return '`{{FIELD_NAME}}` bigint(20) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
        }

        if (preg_match('/^is_/', $field['name'])) {
            return '`{{FIELD_NAME}}` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'{{FIELD_TITLE}}\',';
        }

        if ($field['rule'] == 'datetime') {
            return '`{{FIELD_NAME}}` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'{{FIELD_TITLE}}\',';
        }

        if ($field['rule'] == 'text') {
            return '`{{FIELD_NAME}}` TEXT COMMENT \'{{FIELD_TITLE}}\',';
        }

        return '`{{FIELD_NAME}}` varchar(100) NOT NULL COMMENT \'{{FIELD_TITLE}}\',';
    }
}

class Action extends Node
{
    protected $params = [];
}

class Relations
{
    static public $relation_types = [
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
