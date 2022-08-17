<?php

namespace generator\node;

use generator\Cache;
use generator\driver\Driver;
use generator\helper\FileHelper;
use generator\helper\TemplateHelper;

/**
 * Class Application
 * @package generator\node
 */
class Application extends Node
{
    //应用的根命名空间
    protected $namespace = '';
    //入口文件名称，不需要输入 .php 后缀
    protected $portal = 'index';
    //模块列表
    protected $modules = [];
    protected $dbEngine = 'InnoDB';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        //创建入口文件
        $config = Cache::getInstance()->get('config');
        if ($config['actions']['portal']) {
            Driver::load('php\\Portal', [
                'path' => Cache::getInstance()->get('paths')['public'],
                'file_name' => $this->portal . '.php',
                'template' => TemplateHelper::fetchTemplate('portal'),
                'data' => $this->data
            ])->execute()->writeToFile();
        }

        if ($config['actions']['copy']) {
            //拷贝应用文件
            FileHelper::copyFiles(RESOURCE_PATH . '/thinkphp/app', $this->path);
        }

        //设置应用根命名空间
        Cache::getInstance()->set('root_name_space', $this->namespace);
        //设置数据库引擎
        Cache::getInstance()->set($this->namespace . '_dbEngine', $this->dbEngine);
        $this->processChildren('module');
    }

    public function setNameSpace()
    {
        $this->data['namespace'] = $this->namespace;
    }
}
