<?php
namespace thinkbuilder\node;

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
    protected $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    protected $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    protected $relations = [];

    public function process()
    {
        FileHelper::mkdir($this->path);
    }

    public function setNameSpace()
    {
        $this->namespace = "{$this->parent_namespace}\\model";
        $this->data['namespace'] = $this->namespace;
    }
}