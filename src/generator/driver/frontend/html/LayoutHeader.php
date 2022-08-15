<?php

namespace generator\driver\html;

use generator\Cache;
use generator\driver\Driver;
use generator\helper\TemplateHelper;

class LayoutHeader extends Driver
{
    public function execute(): Driver
    {
        $tags = [
            'TITLE' => Cache::getInstance()->get('company') . ':' . $this->params['caption']
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}
