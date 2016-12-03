<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class DBConfig extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['PROJECT_NAME' => $this->params['data']['namespace'], 'APP_NAME' => $this->params['data']['name']], $content);
        $this->content = $content;
        return $this;
    }
}