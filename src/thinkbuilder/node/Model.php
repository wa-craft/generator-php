<?php
namespace thinkbuilder\node;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Model
 * @package thinkbuilder\node
 */
class Model extends Node
{
    //模型下的字段
    public $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    public $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    public $relations = [];

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);
        FileHelper::mkdir(Cache::getInstance()->get('paths')['database']);

        Generator::create('php\\Model', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('model'),
            'data' => $this->data
        ])->generate()->writeToFile();

        Generator::create('sql\\Model', [
            'path' => $this->path,
            'file_name' => $this->name . '.php',
            'template' => TemplateHelper::fetchTemplate('model'),
            'data' => $this->data
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = "{$this->parent_namespace}\\model";
        $this->data['namespace'] = $this->namespace;
    }
}