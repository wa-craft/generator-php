<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class ConfigData extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['NAMESPACE' => $this->params['data']['namespace']], $content);
        $this->content = $content;
        return $this;
    }
}