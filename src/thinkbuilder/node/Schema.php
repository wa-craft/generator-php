<?php
namespace thinkbuilder\node;

/**
 * Class Schema 模式
 * @package thinkbuilder\node
 */
class Schema extends Node
{
    //模型下的字段
    public $fields = [];
    //模型是否打开自动写入时间字段 create_time&update_time
    public $autoWriteTimeStamp = false;
    //模型与其他模型的关系
    public $relations = [];

    public function process(){}

    public function setNameSpace(){}
}