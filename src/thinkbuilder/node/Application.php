<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\FileHelper;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Application
 * @package thinkbuilder\node
 */
class Application extends Node
{
    //应用的根命名空间
    protected $namespace = '';
    //入口文件名称，不需要输入 .php 后缀
    protected $portal = 'index';

    public function process()
    {
        //创建目录
        $_app_path = $this->paths['target'] . '/' . APP_PATH . '/' . $this->name;
        echo "INFO: creating application directory: {$_app_path} ..." . PHP_EOL;
        FileHelper::mkdir($_app_path);

        //创建入口文件
        if ($this->config['actions']['portal']) {
            Generator::create('php\\Portal', [
                'path' => $this->paths['public'],
                'file_name' => $this->portal . '.php',
                'template' => TemplateHelper::fetchTemplate('portal'),
                'data' => $this->data
            ])->generate()->writeToFile();
        }

        if ($this->config['actions']['copy']) {
            //拷贝应用文件
            FileHelper::copyFiles(ASSETS_PATH . '/thinkphp/application', $_app_path);
        }

        //写入 config / database 配置文件
        Generator::create('php\\ConfigData', [
            'path' => $_app_path,
            'file_name' => 'config.php',
            'template' => TemplateHelper::fetchTemplate('config'),
            'data' => $this->data
        ])->generate()->writeToFile();

        Generator::create('php\\DBConfig', [
            'path' => $_app_path,
            'file_name' => 'database.php',
            'template' => TemplateHelper::fetchTemplate('database'),
            'data' => $this->data
        ])->generate()->writeToFile();

        $this->processChildren('module');
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\\' . $this->name;
    }
}