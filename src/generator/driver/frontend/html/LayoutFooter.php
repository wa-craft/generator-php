<?php

namespace generator\driver\html;

use generator\Cache;
use generator\driver\Generator;
use generator\helper\TemplateHelper;

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
