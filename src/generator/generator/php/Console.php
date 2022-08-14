<?php

namespace generator\generator\php;

use generator\generator\Generator;
use generator\helper\TemplateHelper;

/**
 * Class Console 命令行文件生成器
 * @package generator\generator\php
 */
class Console extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['APP_PATH' => $this->params['data']['name'], 'APP_NAMESPACE' => $this->params['data']['namespace']], $content);
        $this->content = $content;
        return $this;
    }
}
