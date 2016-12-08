<?php
namespace thinkbuilder\node;

use thinkbuilder\Builder;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Validate
 * @package thinkbuilder\node
 */
class Validate extends Node
{
    //校验器下的规则
    public $fields = [];

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        Generator::create('php\\Validate', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('validate'),
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\validate';
        $this->data['namespace'] = $this->namespace;
    }
}