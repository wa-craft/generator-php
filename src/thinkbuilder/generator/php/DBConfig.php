<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class DBConfig 数据库配置文件生成器
 * @package thinkbuilder\generator\php
 */
class DBConfig extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['NAMESPACE' => $this->params['data']['namespace'], 'APP_NAME' => $this->params['data']['name']], $content);
        $this->content = $content;
        return $this;
    }
}