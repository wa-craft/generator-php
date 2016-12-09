<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Helper 帮助程序
 * @package thinkbuilder\node
 */
class Helper extends Node
{
    //控制器下的方法
    public $actions = [];
    public $fields = [];
    //控制器默认的父控制器，如果设置此参数，则会忽略模块统一的默认父控制器设置
    public $parent_controller = '';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        Generator::create('php\\Helper', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('class'),
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\helper';
        $this->data['namespace'] = $this->namespace;
    }
}