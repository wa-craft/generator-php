<?php
namespace thinkbuilder\node;

/**
 * Class Relation
 * @package thinkbuilder\node
 */
class Relation extends Node
{
    static public $types = [
        'hasOne',
        'hasMany',
        'belongsTo',
        'belongsToMany'
    ];
    public $type = 0;
    public $model = '';
    public $this_key = '';
    public $that_key = '';

    public function process(){}
    public function setNameSpace(){}
}