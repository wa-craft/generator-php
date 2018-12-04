<?php
namespace tf\index\model;

use think\Model;

class Relation extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 作者
     */
    public function author()
    {
        return $this->hasOne('common/User', 'id', 'author_id');
    }

}
