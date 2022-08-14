<?php

namespace generator\generator\html;

use generator\Cache;
use generator\generator\Generator;
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
