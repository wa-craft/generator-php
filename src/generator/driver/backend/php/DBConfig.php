<?php

namespace generator\driver\php;

use generator\driver\Generator;
use generator\helper\TemplateHelper;

/**
 * Class DBConfig 数据库配置文件生成器
 * @package generator\driver\php
 */
class DBConfig extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['APP_NAME' => $this->params['data']['name']], $content);
        $this->content = $content;
        return $this;
    }
}
