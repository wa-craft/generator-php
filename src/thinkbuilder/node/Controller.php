<?php
namespace thinkbuilder\node;

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
    public $actions = [];
    public $fields = [];
    //控制器默认的父控制器，如果设置此参数，则会忽略模块统一的默认父控制器设置
    public $parent_controller = '';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        //如果父控制器为空或者是默认的 \think\Controller，则使用没有 CRUD 方法的 class 模板生成代码
        $template = $this->parent_controller == '' || $this->parent_controller == '\\think\\Controller' ? TemplateHelper::fetchTemplate('class') : TemplateHelper::fetchTemplate('controller');
        if($this->name == 'Error') $template = TemplateHelper::fetchTemplate('error');
        Generator::create('php\\Controller', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => $template,
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\controller';
        $this->data['namespace'] = $this->namespace;
    }
}