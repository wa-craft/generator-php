<?php
namespace thinkbuilder\generator;

use thinkbuilder\helper\TemplateHelper;

/**
 * Class ConfigGenerator config|database 应用配置文件生成
 * @package thinkbuilder\generator
 */
class ConfigGenerator extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        switch ($this->params['type']) {
            case 'config':
                $content = TemplateHelper::parseTemplateTags(['NAMESPACE' => $this->params['data']['namespace']], $content);
                break;
            case 'database':
                $content = TemplateHelper::parseTemplateTags(['PROJECT_NAME' => $this->params['data']['namespace'], 'APP_NAME' => $this->params['data']['name']], $content);
                break;
        }
        $this->content = $content;
        return $this;
    }
}