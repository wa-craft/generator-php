<?php
namespace thinkbuilder;

use thinkbuilder\generator\{
    IGenerator, Generator, HtmlGenerator, JSGenerator, MenuGenerator, ProfileGenerator, PHPGenerator, SQLGenerator
};

use thinkbuilder\node\{
    Action, Application, Controller, Field, Model, Module, Node, Project, Relation, Traits, View, Validate
};

use thinkbuilder\helper\{
    TemplateHelper, FileHelper, ClassHelper
};

/**
 * Class Builder 构建程序
 */
class Builder
{
    //配置参数
    private $config = [];
    //系统基本路径
    private $paths = [
        'target' => './deploy',
        'application' => './deploy/' . APP_PATH,
        'database' => './deploy/' . DBFILE_PATH,
        'profile' => './deploy/' . PROFILE_PATH,
        'public' => './deploy/' . PUB_PATH
    ];

    //数据
    private $data = [];
    //默认的 git 仓库
    public $repository = '';

    //版本
    protected $version = '1.3.0';

    public function __construct($params = [])
    {
        if (key_exists('config', $params)) $this->setConfigFromFile($params['config']);
        if (key_exists('data', $params)) $this->setDataFromFile($params['data']);
        if (key_exists('target', $params)) $this->paths['target'] = $params['target'];
        if (key_exists('repository', $params)) $this->repository = $params['repository'];
    }

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
        $this->config = require $file;
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
        $this->data = require $file;
    }

    protected function gitClone()
    {
        $cmd = 'git clone ' . $this->repository . ' ' . $this->paths['target'] . ' && ' . 'rm -rf ' . $this->paths['target'] . '/.git';
        shell_exec($cmd);
    }

    protected function makeBaseDirectories()
    {
        $this->paths = array_merge($this->paths, [
            'application' => $this->paths['target'] . '/' . APP_PATH,
            'database' => $this->paths['target'] . '/' . DBFILE_PATH,
            'profile' => $this->paths['target'] . '/' . PROFILE_PATH,
            'public' => $this->paths['target'] . '/' . PUB_PATH
        ]);

        FileHelper::mkdirs($this->paths);
    }

    protected function decompressAssets()
    {
        $_assets_file = ASSETS_PATH . '/themes/' . $this->config['theme'] . '/assets.tar.bz2';
        $cmd = 'tar xvjf ' . $_assets_file . ' -C' . $this->paths['public'];
        shell_exec($cmd);
    }

    /**
     * 创建项目文件的主方法
     */
    public function build()
    {
        $build_actions = $this->config['actions'];

        //使用 git clone 创建初始目录结构
        $this->gitClone();

        //创建基本目录
        $this->makeBaseDirectories();


        //解压资源文件
        if ($build_actions['decompress_assets']) {
            $this->decompressAssets();
        }

        //装载默认设置并进行缓存
        $cache = Cache::getInstance();
        $cache->set('defaults', $this->config['defaults']);
        $cache->set('config', $this->config);
        $cache->set('paths', $this->paths);

        $project = Node::create('Project', ['data' => $this->data]);
        $project->process();


        /*
                $applications = $project['applications'];
                foreach ($applications as $application) {
                    //如果应用并非是数组，则视为引用已经设定的数组
                    if (!is_array($application)) {
                        $application_file = PACKAGE_PATH . '/$application/' . $application . '.php';
                        if (is_file($application_file)) {
                            $application = require $application_file;
                        } else {
                            continue;
                        }
                    }

                    //创建目录
                    $_app_path = $this->paths['target'] . '/' . APP_PATH . '/' . $application['name'];
                    echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
                    FileHelper::mkdir($_app_path);

                    //创建入口文件
                    if ($build_actions['portal']) {
                        $_portal = (isset($application['portal'])) ? $application['portal'] : $application['name'];
                        TemplateHelper::write_php($this->paths['public'], ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['namespace'], TemplateHelper::$templates);
                    }

                    if ($build_actions['copy']) {
                        //拷贝应用文件
                        FileHelper::copyFiles(ASSETS_PATH . '/thinkphp/application', $_app_path);
                    }

                    //写入 config / database 配置文件
                    TemplateHelper::write_config($_app_path, 'config', TemplateHelper::$templates, ['NAMESPACE' => $application['namespace']]);
                    TemplateHelper::write_config($_app_path, 'database', TemplateHelper::$templates, ['PROJECT_NAME' => $project['name'], 'APP_NAME' => $application['name']]);

                    $modules = $application['modules'];
                    foreach ($modules as $module) {
                        //如果模块并非是数组，则视为引用已经设定的数组
                        if (!is_array($module)) {
                            $module_file = PACKAGE_PATH . '/modules/' . $module . '.php';
                            if (is_file($module_file)) {
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
                                Controller::writeToFile($_controller_path, $module, ['name' => 'controller'], $controller, $application['namespace'], TemplateHelper::$templates);
                                //生成VIEW模板
                                if ($build_actions['view']) {
                                    if (!isset($controller['actions'])) $controller['actions'] = $this->defaults['actions'];

                                    //生成方法的前台页面
                                    $_view_model_path = $_view_path . '/' . strtolower($controller['name']);
                                    foreach ($controller['actions'] as $action) {
                                        View::writeToFile($_view_model_path, $module, $action, $controller, TemplateHelper::$templates);
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
                                Traits::writeToFile($_traits_path, $module, ['name' => 'traits'], $trait, $application['namespace'], TemplateHelper::$templates);
                            }
                        }


                        //根据模块生成代码
                        $models = $module['models'];
                        foreach ($models as $model) {
                            //判断是否存在 autoWriteTimeStamp 参数
                            if (isset($model['autoWriteTimeStamp'])) {
                                if ($model['autoWriteTimeStamp']) {
                                    $model['fields'] = array_merge($model['fields'], $this->defaults['autoTimeStampFields']);
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
                                        TemplateHelper::write_sql($this->paths['database'], ['name' => 'sql_table'], $_tmp_model, $application['namespace'], TemplateHelper::$templates);
                                    }
                                }
                                $model['fields'] = array_merge($r_fields, $model['fields']);
                            }

                            if ($build_actions['controller']) {
                                //生成CRUD控制器
                                Controller::writeToFile($_controller_path, $module, ['name' => 'controller'], $model, $application['namespace'], TemplateHelper::$templates);
                            }

                            if ($build_actions['model']) {
                                //生成模型
                                $_model_path = $_module_path . '/model';
                                Model::writeToFile($_model_path, $module, ['name' => 'model'], $model, $application['namespace'], TemplateHelper::$templates);
                            }

                            if ($build_actions['validate']) {
                                //生成校验器
                                $_validate_path = $_module_path . '/validate';
                                Validate::writeToFile($_validate_path, ['name' => 'common'], ['name' => 'validate'], $model, $application['namespace'], TemplateHelper::$templates);
                            }

                            //生成VIEW模板
                            if ($build_actions['view']) {
                                if (!isset($model['actions'])) $model['actions'] = $this->defaults['actions'];

                                //生成方法的前台页面
                                $_view_model_path = $_view_path . '/' . strtolower($model['name']);
                                foreach ($model['actions'] as $action) {
                                    View::writeToFile($_view_model_path, $module, $action, $model, TemplateHelper::$templates);
                                }
                            }
                            //生成 sql
                            if ($build_actions['sql']) {
                                TemplateHelper::write_sql($this->paths['database'], ['name' => 'sql_table'], $model, $application['namespace'], TemplateHelper::$templates);
                            }
                        }
                        if ($build_actions['copy']) {
                            //拷贝基础文件
                            FileHelper::copyFiles(ASSETS_PATH . '/themes/' . $this->config['theme'] . '/layout', $_view_path . '/layout');
                        }
                    }
                }*/

        //执行composer update命令
        if ($build_actions['run_composer']) {
            $cmd = 'cd ' . $this->paths['target'];
            shell_exec($cmd);
            echo 'updating composer repositories ...' . PHP_EOL;
            $cmd = 'composer update';
            shell_exec($cmd);
        }

        //执行bower install命令
        if ($build_actions['run_bower']) {
            $cmd = 'cd ' . $this->paths['target'];
            shell_exec($cmd);
            echo 'installing bower repositories ...' . PHP_EOL;
            $deps = $this->defaults['bower_deps'];
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
