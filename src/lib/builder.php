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
        'traits' => true,
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
        //是否生成目录数组，暂时保留未应用
        'menu' => true,
        //是否解压资源文件
        'decompress_assets' => false,
        //是否最后运行composer update命令
        'run_composer' => false,
        //是否最后运行 bower update 命令
        'run_bower' => false
    ];
    //版本
    protected $version = '1.2.0';

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

        FileHelper::mkdir($this->target_path);
        FileHelper::mkdir($application_path);
        FileHelper::mkdir($database_path);
        FileHelper::mkdir($profile_path);
        FileHelper::mkdir($public_path);

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
            TemplateHelper::write_nginx($profile_path, $templates['nginx'], $project['domain'], $applications);
        }

        //生成 apache htaccess 配置文件
        if ($build_actions['apache']) {
            TemplateHelper::write_apache($public_path, $templates['apache'], $applications);
        }

        //解压资源文件
        if ($build_actions['decompress_assets']) {
            $_assets_file = SHARE_PATH . '/assets/' . $defaults['default_theme'] . '/assets.tar.bz2';
            $cmd = 'tar xvjf ' . $_assets_file . ' -C' . $public_path;
            shell_exec($cmd);
        }

        foreach ($applications as $application) {
            //如果应用并非是数组，则视为引用已经设定的数组
            if (!is_array($application)) {
                $application_file = PACKAGE_PATH . '/$application/' . $application.'.php';
                if(is_file($application_file)) {
                    $application = require $application_file;
                } else {
                    continue;
                }
            }

            //创建目录
            $_app_path = $this->target_path . '/' . APP_PATH . '/' . $application['name'];
            echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
            FileHelper::mkdir($_app_path);

            //创建入口文件
            if ($build_actions['portal']) {
                $_portal = (isset($application['portal'])) ? $application['portal'] : $application['name'];
                TemplateHelper::write_php($public_path, ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['namespace'], $templates);
            }

            if ($build_actions['copy']) {
                //拷贝应用文件
                FileHelper::copyFiles(SHARE_PATH . '/application', $_app_path);
            }

            //写入 config / database 配置文件
            TemplateHelper::write_config($_app_path, 'config', $templates, ['NAMESPACE' => $application['namespace']]);
            TemplateHelper::write_config($_app_path, 'database', $templates, ['PROJECT_NAME' => $project['name'], 'APP_NAME' => $application['name']]);

            $modules = $application['modules'];
            foreach ($modules as $module) {
                //如果模块并非是数组，则视为引用已经设定的数组
                if (!is_array($module)) {
                    $module_file = PACKAGE_PATH . '/modules/' . $module.'.php';
                    if(is_file($module_file)) {
                        $module = require $module_file;
                    } else {
                        continue;
                    }
                }

                //创建模块目录
                $_module_path = $_app_path . '/' . $module['name'];
                FileHelper::mkdir($_module_path);

                //生成代码

                //生成单独控制器代码
                $_controller_path = $_module_path . '/controller';
                FileHelper::mkdir($_controller_path);
                $_view_path = $_module_path . '/view';
                FileHelper::mkdir($_view_path);

                if ($build_actions['controller']) {
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
                }

                //生成 traits 的代码
                if (isset($module['traits']) && $build_actions['traits']) {
                    $_traits_path = $_module_path . '/traits';
                    FileHelper::mkdir($_traits_path);
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

                    //根据 relations 来生成 xx_id 形式的 field
                    if (isset($model['relations'])) {
                        $r_fields = [];
                        foreach ($model['relations'] as $relation) {
                            if ($relation['type'] != 'belongsToMany') {
                                if ($relation['this_key'] != 'id') {
                                    $r_fields[] = ['name' => lcfirst($relation['this_key']), 'title' => $relation['caption'], 'rule' => 'number', 'required' => true, 'is_unique' => false];
                                }
                            } else {
                                //自动生成多对多关系的中间表
                                $_tmp_model = [
                                    'name' => $relation['model'],
                                    'comment' => $relation['caption'],
                                    'autoWriteTimeStamp' => false,
                                    'fields' => [
                                        ['name' => $relation['this_key'], 'title' => '', 'rule' => 'number', 'required' => true, 'is_unique' => false],
                                        ['name' => $relation['that_key'], 'title' => '', 'rule' => 'number', 'required' => true, 'is_unique' => false]
                                    ]
                                ];
                                TemplateHelper::write_sql($database_path, ['name' => 'sql_table'], $_tmp_model, $application['namespace'], $templates);
                            }
                        }
                        $model['fields'] = array_merge($r_fields, $model['fields']);
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
                        TemplateHelper::write_sql($database_path, ['name' => 'sql_table'], $model, $application['namespace'], $templates);
                    }
                }
                if ($build_actions['copy']) {
                    //拷贝基础文件
                    FileHelper::copyFiles(SHARE_PATH . '/html/layout', $_view_path . '/layout');
                }
            }
        }

        //执行composer update命令
        if ($build_actions['run_composer']) {
            $cmd = 'cd ' . $this->target_path;
            shell_exec($cmd);
            echo 'updating composer repositories ...' . PHP_EOL;
            $cmd = 'composer update';
            shell_exec($cmd);
        }

        //执行bower install命令
        if ($build_actions['run_bower']) {
            $cmd = 'cd ' . $this->target_path;
            shell_exec($cmd);
            echo 'installing bower repositories ...' . PHP_EOL;
            $deps = $defaults['bower_deps'];
            $cmd = 'bower install ';
            foreach ($deps as $dep) {
                $cmd .= $dep . ' ';

            }
            $cmd .= '--save';
            if (count($deps) != 0) shell_exec($cmd);
        }

        echo "ThinkForge Builder, Version: " . $this->version . PHP_EOL;
    }
}
