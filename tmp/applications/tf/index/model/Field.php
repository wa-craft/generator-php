<?php
namespace tf\index\model;

use think\Model;

class Field extends Model
{
    protected $autoWriteTimestamp = false;

    /**
     * 模型
     */
    public function model()
    {
        return $this->belongsTo('Model', 'model_id', 'id');
    }

}
