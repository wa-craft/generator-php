<?php
namespace thinkbuilder\node;

/**
 * Class Action
 * @package thinkbuilder\node
 */
class Action extends Node
{
    protected $params = [];
    public function process()
    {

    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace;
        $this->data['namespace'] = $this->namespace;
    }
}