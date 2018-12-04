<?php
namespace tf\index\model;

use think\Model;

class Theme extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 方法
     */
    public function actions()
    {
        return $this->hasMany('ControllerAction', 'controller_id', 'id');
    }

    /**
     * 模块
     */
    public function module()
    {
        return $this->belongsTo('Module', 'module_id', 'id');
    }

}
