<?php
namespace tf\index\model;

use think\Model;

class PhpClass extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 类的类型，包括：CRUD Controller|Plain Controller|Helper|Behavior|Trait
     */
    public function type()
    {
        return $this->hasOne('Option', 'id', 'type');
    }

    /**
     * 方法
     */
    public function parent_controller()
    {
        return $this->hasMany('Action', 'controller_id', 'id');
    }

    /**
     * 引用的泛型
     */
    public function traits()
    {
        return $this->belongsToMany('PhpClassTrait', 'class_id', 'id');
    }

    /**
     * 方法
     */
    public function actions()
    {
        return $this->hasMany('Action', 'controller_id', 'id');
    }

    /**
     * 模块
     */
    public function module()
    {
        return $this->belongsTo('Module', 'module_id', 'id');
    }

}
