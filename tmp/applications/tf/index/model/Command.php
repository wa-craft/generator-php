<?php
namespace tf\index\model;

use think\Model;

class Command extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 项目
     */
    public function project()
    {
        return $this->belongsTo('Project', 'project_id', 'id');
    }

}
