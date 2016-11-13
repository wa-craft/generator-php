<?php

/**
 * Class Builder 构建程序
 */
class Builder
{
    //配置参数
    protected $config = [];
    //数据
    protected $data = null;
    protected $target_path = '';
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
        'apache' => true
    ];
    //版本
    protected $version = '1.0.0';

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
        $this->config = require_once($file);
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
        $this->data = require_once($file);
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
        $defaults = $this->config['defaults'];

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
                write_template_file($public_path, ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['namespace'], $templates);
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
                    write_php($_controller_path, $module, ['name' => 'controller'], $controller, $application['namespace'], $templates);
                    //生成VIEW模板
                    if ($build_actions['view']) {
                        if (!isset($controller['actions'])) $controller['actions'] = $defaults['actions'];

                        //生成方法的前台页面
                        $_view_model_path = $_view_path . '/' . strtolower($controller['name']);
                        foreach ($controller['actions'] as $action) {
                            write_template_file($_view_model_path, $module, $action, $controller, $application['namespace'], $templates, '.html');
                        }
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
                        write_template_file($_controller_path, $module, ['name' => 'controller'], $model, $application['namespace'], $templates);
                    }

                    if ($build_actions['model']) {
                        //生成模型
                        $_model_path = $_module_path . '/model';
                        write_template_file($_model_path, $module, ['name' => 'model'], $model, $application['namespace'], $templates);
                    }

                    if ($build_actions['validate']) {
                        //生成校验器
                        $_validate_path = $_module_path . '/validate';
                        write_template_file($_validate_path, ['name' => 'common'], ['name' => 'validate'], $model, $application['namespace'], $templates);
                    }

                    //生成VIEW模板
                    if ($build_actions['view']) {
                        if (!isset($model['actions'])) $model['actions'] = $defaults['actions'];

                        //生成方法的前台页面
                        $_view_model_path = $_view_path . '/' . strtolower($model['name']);
                        foreach ($model['actions'] as $action) {
                            write_template_file($_view_model_path, $module, $action, $model, $application['namespace'], $templates, '.html');
                        }
                    }
                    //生成 sql
                    if ($build_actions['sql']) {
                        write_template_file($database_path, $module, ['name' => 'sql_table'], $model, $application['namespace'], $templates, '.sql');
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

    public function init($params = [])
    {

    }

    public function process()
    {
        //创建目录
        //拷贝文件
        //处理模板文件
        //写入文件
    }

    public function makeDir()
    {

    }

    public function copyFiles($files = [])
    {

    }

    public function writeToFile()
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
}

class Controller extends Node
{
    //控制器下的方法
    protected $actions = [];
}

class Model extends Node
{
    //模型下的字段
    protected $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    protected $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    protected $relations = [];
}

class Validate extends Node
{
    //校验器下的规则
    protected $rules = [];
}

class View extends Node
{
    //视图使用的布局
    protected $layout = '';
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
        'belongsTo'
    ];
    protected $type = 0;
    protected $model = null;
}
