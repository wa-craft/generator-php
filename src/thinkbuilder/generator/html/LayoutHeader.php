<?php
namespace thinkbuilder\generator\html;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\TemplateHelper;

class LayoutHeader extends Generator
{
    public function generate(): Generator
    {
        $tags = [
            'TITLE' => Cache::getInstance()->get('company').':'. $this->params['caption']
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}