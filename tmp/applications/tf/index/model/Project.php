<?php
namespace tf\index\model;

use think\Model;

class Project extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 模块
     */
    public function applications()
    {
        return $this->hasMany('Application', 'project_id', 'id');
    }

    /**
     * 命令
     */
    public function commands()
    {
        return $this->hasMany('Command', 'project_id', 'id');
    }

}
