#!/usr/bin/env php
<?php
/**
 * thinkphp v5 脚手架创建工具
 */
//读取相关程序
require "./lib/defines.php";
require "./lib/functions.php";
require "./lib/classes.php";
require "./vendor/autoload.php";

//设置命令
$cmd = new \Commando\Command();

$cmd->option()
    ->description('A command line toolit to build MVC scaffold for thinkphp v5.*');

//配置文件
$cmd->option('c')
    ->aka('config')
    ->describedAs('define a configuration file, default is : ./config.php')
    ->default('./config.php')
    ->map(function ($config) {
        return $config;
    });
//项目
$cmd->option('p')
    ->aka('project')
    ->describedAs('define a project data file, the file must exists in ./project.')
    ->default('./project/project.php')
    ->map(function ($project) {
        $file = './project/' . $project . '.php';
        return $file;
    });
//创建动作
$cmd->option('a')
    ->aka('actions')
    ->describedAs('define build actions, all|mvc|copy')
    ->default('all')
    ->map(function ($actions) {
        return $actions;
    });
//目标路径
$cmd->option('t')
    ->aka('target')
    ->describedAs('define a target directory, default is : ' . TAR_ROOT_PATH)
    ->default(TAR_ROOT_PATH)
    ->map(function ($target) {
        return $target;
    });

$builder = new Builder();
$builder->setConfigFromFile($cmd['config']);
$builder->setDataFromFile($cmd['project']);
$builder->setTargetPath($cmd['target']);
$builder->build();
