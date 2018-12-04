<?php
namespace tf\index\model;

use think\Model;

class Module extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 主题
     */
    public function theme()
    {
        return $this->hasOne('Theme', 'id', 'theme_id');
    }

    /**
     * 控制器
     */
    public function controllers()
    {
        return $this->hasMany('PhpClass', 'module_id', 'id');
    }

    /**
     * 控制器
     */
    public function helpers()
    {
        return $this->hasMany('PhpClass', 'module_id', 'id');
    }

    /**
     * 控制器
     */
    public function traits()
    {
        return $this->hasMany('PhpClass', 'module_id', 'id');
    }

    /**
     * 模型
     */
    public function models()
    {
        return $this->hasMany('Controller', 'module_id', 'id');
    }

    /**
     * 应用
     */
    public function applications()
    {
        return $this->belongsTo('Application', 'application_id', 'id');
    }

}
