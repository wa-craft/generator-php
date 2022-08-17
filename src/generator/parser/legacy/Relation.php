<?php

namespace generator\parser\legacy;

/**
 * Class Relation
 * @package generator\parser\legacy
 */
class Relation extends Node
{
    public static $types = [
        'hasOne',
        'hasMany',
        'belongsTo',
        'belongsToMany'
    ];
    public $type = 0;
    public $model = '';
    public $this_key = '';
    public $that_key = '';

    public function process()
    {
    }
    public function setNameSpace()
    {
    }
}
