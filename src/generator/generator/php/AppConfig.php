<?php

namespace generator\generator\php;

use generator\generator\Generator;
use generator\helper\TemplateHelper;

/**
 * Class ConfigData $application/config.php 配置文件生成器
 * @package thinkbuilder\generator\php
 */
class AppConfig extends Generator
{
    public function generate(): Generator
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
