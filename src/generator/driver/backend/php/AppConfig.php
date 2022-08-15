<?php

namespace generator\driver\php;

use generator\driver\Driver;
use generator\helper\TemplateHelper;

/**
 * Class ConfigData $application/config.php 配置文件生成器
 * @package generator\driver\php
 */
class AppConfig extends Driver
{
    public function execute(): Driver
    {
        $data = $this->params['data'];
        $content = $this->params['template'];
        $tags = [
            /*'NAMESPACE' => $data['namespace'],*/
        ];

        $content = TemplateHelper::parseTemplateTags($tags, $content);
        $this->content = $content;
        return $this;
    }
}
