<?php
namespace thinkbuilder\node;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\{
    TemplateHelper, FileHelper
};

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
        echo "n: " . $this->namespace;
        $this->data['namespace'] = $this->namespace;
    }
}