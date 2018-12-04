<?php
namespace tf\index\model;

use think\Model;

class Application extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 模块
     */
    public function modules()
    {
        return $this->hasMany('Module', 'application_id', 'id');
    }

    /**
     * 项目
     */
    public function project()
    {
        return $this->belongsTo('Project', 'project_id', 'id');
    }

}
