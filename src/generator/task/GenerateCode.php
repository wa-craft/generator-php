<?php

declare(strict_types=1);

namespace generator\task;

use generator\Context;
use generator\helper\FileHelper;
use generator\parser\component\Schema;
use generator\template\StereoType;

class GenerateCode extends Task
{
    private ?StereoType $stereoType = null;
    private ?Schema $schema = null;

    public function __construct(array $params)
    {
        $this->stereoType = $params['stereoType'] ?: null;
        $this->schema = $params['schema'] ?: [];
    }

    public function execute()
    {
        //读取模板
        $context = Context::getInstance();
        $tmpl_file = TEMPLATE_PATH . '/' . $this->stereoType->lang . '/' . $this->stereoType->name . '.mustache';
        $content = file_get_contents($tmpl_file);
        $me = new \Mustache_Engine();
        $new_content = $me->render($content, $this->schema);

        var_dump($new_content);
    }
}
