#!/usr/bin/env php
<?php
/**
 * thinkphp v5 脚手架创建工具
 */
//读取相关程序
require_once "./lib/defines.php";
require_once "./lib/functions.php";
require_once "./lib/classes.php";
require "./vendor/autoload.php";

//设置命令
$cmd = new \Commando\Command();

$cmd->option('c')
    ->aka('config')
    ->describedAs('define a configuration file, default is : ./config.php')
    ->default('./config.php')
    ->map(function ($config) {
        return $config;
    });

$cmd->option('p')
    ->aka('project')
    ->describedAs('define a project data file, the file must exists in ./project.')
    ->default('./project/project.php')
    ->map(function ($project) {
        $file = './project/' . $project . '.php';
        return $file;
    });

$builder = new Builder();
$builder->setConfigFromFile($cmd['config']);
$builder->setDataFromFile($cmd['project']);
$builder->build();
