#!/usr/bin/env php
<?php
/**
 * thinkphp v5 脚手架创建工具
 */
//定义基本数据
define('TAR_ROOT_PATH', './deploy/');
define('APP_PATH', 'application');
define('DBFILE_PATH', TAR_ROOT_PATH . 'database/');
define('PROFILE_PATH', TAR_ROOT_PATH . 'profile/');
define('TMPL_PATH', './template');
define('PUB_PATH', TAR_ROOT_PATH . '/public/');
define('SHARE_PATH', './share');
define('VERSION', '0.6.3');
define('ROOT_REPOS', 'https://github.com/goldeagle/bforge-think');

require_once "./lib/functions.php";

$config = require_once './config.php';

$build_actions = $config['build_actions'];

//使用 git clone 创建初始目录结构
$cmd = 'git clone ' . ROOT_REPOS . ' ' . TAR_ROOT_PATH . ' && ' . 'rm -rf ' . TAR_ROOT_PATH . '.git';
shell_exec($cmd);

//创建基本目录
mk_dir(TAR_ROOT_PATH);
mk_dir(TAR_ROOT_PATH . APP_PATH);
mk_dir(DBFILE_PATH);
mk_dir(PROFILE_PATH);


//装载模板文件
echo "INFO: reading templates ..." . PHP_EOL;
$templates = $config['templates'];

//装载默认设置
$defaults = $config['defaults'];

$project = require "./project/project.php";

$applications = $project['applications'];

//生成 nginx 配置文件
if ($build_actions['nginx']) {
    write_nginx(PROFILE_PATH, $templates['nginx'], $project['domain'], $applications);
}

//生成 apache htaccess 配置文件
if ($build_actions['apache']) {
    write_apache(PUB_PATH, $templates['apache'],$applications);
}

foreach ($applications as $application) {
    //创建目录
    $_app_path = TAR_ROOT_PATH . APP_PATH . '/' . $application['name'];
    echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
    mk_dir($_app_path);

    //创建入口文件
    if ($build_actions['portal']) {
        $_portal = (isset($application['portal'])) ? $application['portal'] : $application['name'];
        write_template_file(PUB_PATH, ['name' => ''], ['name' => 'portal'], ['name' => $_portal], $application['namespace'], $templates);
    }

    if ($build_actions['copy']) {
        //拷贝应用文件
        copy_files(SHARE_PATH . '/php', $_app_path);
    }

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
            write_template_file($_controller_path, $module, ['name' => 'controller'], $controller, $application['namespace'], $templates);
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
            if(isset($model['autoWriteTimeStamp'])) {
                if($model['autoWriteTimeStamp']) {
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
                write_template_file(DBFILE_PATH, $module, ['name' => 'sql_table'], $model, $application['namespace'], $templates, '.sql');
            }
        }
        if ($build_actions['copy']) {
            //拷贝基础文件
            copy_files(SHARE_PATH . '/html/layout', $_view_path . '/layout');
        }
    }
}

echo "ThinkForge Builder, Version: " . VERSION . PHP_EOL;

//拷贝默认数据库SQL文件

//自动导入数据库SQL文件

//TODO 设置模型的类型，比如：树状、键值对等，程序可以根据模型的类型，自动选择不同的界面与控制器模板
