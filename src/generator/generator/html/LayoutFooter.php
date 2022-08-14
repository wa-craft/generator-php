<?php
namespace generator\generator\html;

use generator\Cache;
use generator\generator\Generator;
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