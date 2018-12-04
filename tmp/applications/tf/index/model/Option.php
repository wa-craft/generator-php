<?php
namespace tf\index\model;

use think\Model;

class Option extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 父节点
     */
    public function parent()
    {
        return $this->hasOne('Option', 'pid', 'id');
    }

}
