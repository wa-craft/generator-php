<?php
namespace thinkbuilder\generator\php;

use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class Portal extends Generator
{
    public function generate(): Generator
    {
        $content = $this->params['template'];
        $content = TemplateHelper::parseTemplateTags(['APP_PATH' => $this->params['data']['name'], 'APP_NAME' => $this->params['data']['portal']], $content);
        $this->content = $content;
        return $this;
    }
}