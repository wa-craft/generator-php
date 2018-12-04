<?php
namespace tf\index\model;

use think\Model;

class Action extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 作用域，包括 public|protected|private
     */
    public function scope()
    {
        return $this->hasOne('Option', 'id', 'scope_id');
    }

    /**
     * 字段
     */
    public function params()
    {
        return $this->hasMany('Parameter', 'action_id', 'id');
    }

    /**
     * 所属对象
     */
    public function phpClass()
    {
        return $this->belongsTo('PhpClass', 'class_id', 'id');
    }

}
