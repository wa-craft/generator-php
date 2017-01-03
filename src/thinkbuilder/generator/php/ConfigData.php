<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class ConfigData $application/config.php 配置文件生成器
 * @package thinkbuilder\generator\php
 */
class ConfigData extends Generator
{
    public function generate(): Generator
    {
        $data = $this->params['data'];
        $content = $this->params['template'];
        $tags = [
            'NAMESPACE' => $data['namespace'],
            //基于NAMESPACE用MD5生成 session_id 配置变量
            //'SESSION_ID' => md5($data['namespace'])
            'SESSION_ID' => ''
        ];

        $content = TemplateHelper::parseTemplateTags($tags, $content);
        $this->content = $content;
        return $this;
    }
}