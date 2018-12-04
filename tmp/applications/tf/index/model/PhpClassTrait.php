<?php
namespace tf\index\model;

use think\Model;

class PhpClassTrait extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 类
     */
    public function class()
    {
        return $this->belongsTo('PhpClass', 'class_id', 'id');
    }

    /**
     * 泛型
     */
    public function trait()
    {
        return $this->belongsTo('Trait', 'trait_id', 'id');
    }

}
