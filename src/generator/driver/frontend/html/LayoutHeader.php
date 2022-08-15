<?php

namespace generator\driver\html;

use generator\Cache;
use generator\driver\Generator;
use generator\helper\TemplateHelper;

class LayoutHeader extends Generator
{
    public function generate(): Generator
    {
        $tags = [
            'TITLE' => Cache::getInstance()->get('company') . ':' . $this->params['caption']
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}
