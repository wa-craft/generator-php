<?php

namespace generator\driver\profile;

use generator\driver\Generator;

/**
 * Class Apache apache 虚拟主机配置文件生成器
 * @package generator\driver\profile
 */
class Apache extends Generator
{
    public function generate(): Generator
    {
        $domain = $this->params['domain'] ?? $this->params['project']['domain'];
        $this->content = str_replace('{{DOMAIN}}', $domain, $this->params['template']);
        return $this;
    }
}
