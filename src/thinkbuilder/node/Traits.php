<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Traits
 * @package thinkbuilder\node
 */
class Traits extends Node
{
    //特征下的方法
    protected $actions = [];

    public function process()
    {
        //创建路径
        FileHelper::mkdir($this->path);
        Generator::create('php\\Traits', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('traits'),
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\\traits';
        $this->data['namespace'] = $this->namespace;
    }
}