<?php

namespace generator\driver\php;

use generator\driver\Driver;
use generator\helper\TemplateHelper;

/**
 * Class Console 命令行文件生成器
 * @package generator\driver\php
 */
class Console extends Driver
{
    public function execute(): Driver
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['APP_PATH' => $this->params['data']['name'], 'APP_NAMESPACE' => $this->params['data']['namespace']], $content);
        $this->content = $content;
        return $this;
    }
}
