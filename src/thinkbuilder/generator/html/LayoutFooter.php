<?php
namespace thinkbuilder\generator\html;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class LayoutFooter extends Generator
{
    public function generate(): Generator
    {
        $tags = [
            'COMPANY_NAME' => Cache::getInstance()->get('company'),
            'TB_VERSION' => VERSION,
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}