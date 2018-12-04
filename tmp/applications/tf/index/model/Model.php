<?php
namespace tf\index\model;

use think\Model;

class Model extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 造型，包括：RowSet|KeyValue|Tree
     */
    public function profile()
    {
        return $this->hasOne('Option', 'module_id', 'id');
    }

    /**
     * 字段
     */
    public function fields()
    {
        return $this->hasMany('Field', 'module_id', 'id');
    }

    /**
     * 关联
     */
    public function relations()
    {
        return $this->hasMany('Relation', 'model_id', 'id');
    }

    /**
     * 模块
     */
    public function module()
    {
        return $this->belongsTo('Module', 'module_id', 'id');
    }

}
