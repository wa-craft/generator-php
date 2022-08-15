<?php

namespace generator\driver\html;

use generator\Cache;
use generator\driver\Driver;
use generator\helper\TemplateHelper;

class LayoutFooter extends Driver
{
    public function execute(): Driver
    {
        $tags = [
            'COMPANY_NAME' => Cache::getInstance()->get('company'),
            'TB_VERSION' => VERSION,
        ];
        $this->content = TemplateHelper::parseTemplateTags($tags, $this->params['template']);

        return $this;
    }
}
