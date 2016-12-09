<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Behavior 行为
 * @package thinkbuilder\node
 */
class Behavior extends Node
{
    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        Generator::create('php\\Behavior', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('behavior'),
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\behavior';
        $this->data['namespace'] = $this->namespace;
    }
}