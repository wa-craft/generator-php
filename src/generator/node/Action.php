<?php

namespace generator\node;

use generator\generator\Generator;
use generator\helper\{
    TemplateHelper, FileHelper
};

/**
 * Class Action
 * @package thinkbuilder\node
 */
class Action extends Node
{
    public $params = [];
    public $is_static = false;

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
