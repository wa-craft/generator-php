<?php

namespace generator\parser\legacy;

use generator\driver\Driver;
use generator\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Traits
 * @package generator\parser\legacy
 */
class Traits extends Node
{
    //特征下的方法
    protected $actions = [];

    public function process()
    {
        //创建路径
        FileHelper::mkdir($this->path);
        Driver::load('php\\Traits', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('traits'),
            'data' => $this->data
        ])->execute()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\\traits';
        $this->data['namespace'] = $this->namespace;
    }
}
