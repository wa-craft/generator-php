<?php
namespace tf\index\model;

use think\Model;

class Trait extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 方法
     */
    public function actions()
    {
        return $this->hasMany('Action', 'class_id', 'id');
    }

    /**
     * 引用泛型的类
     */
    public function classes()
    {
        return $this->belongsToMany('PhpClassTrait', 'trait_id', 'id');
    }

    /**
     * 模块
     */
    public function module()
    {
        return $this->belongsTo('Module', 'module_id', 'id');
    }

}
