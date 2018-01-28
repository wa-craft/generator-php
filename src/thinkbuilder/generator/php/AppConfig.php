<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

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