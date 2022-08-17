<?php

namespace generator\node;

use generator\driver\Driver;
use generator\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Validate
 * @package generator\node
 */
class Validate extends Node
{
    //校验器下的规则
    public $fields = [];

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        Driver::load('php\\Validate', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('validate'),
            'data' => $this->data
        ])->execute()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\validate';
        $this->data['namespace'] = $this->namespace;
    }
}
