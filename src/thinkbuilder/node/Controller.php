<?php
namespace thinkbuilder\node;

use thinkbuilder\Builder;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Controller
 * @package thinkbuilder\node
 */
class Controller extends Node
{
    //控制器下的方法
    protected $actions = [];
    //控制器默认的父控制器，如果设置此参数，则会忽略模块统一的默认父控制器设置
    protected $parent_controller = '';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        $g = Generator::create('php\\Controller', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('controller'),
            'data' => $this->data
        ])->generate()->writeToFile();

        $this->processChildren('action');
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\controller';
        $this->data['namespace'] = $this->namespace;
    }
}