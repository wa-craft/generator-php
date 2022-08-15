<?php

namespace generator\driver\profile;

use generator\driver\Driver;

/**
 * Class Apache apache 虚拟主机配置文件生成器
 * @package generator\driver\profile
 */
class Apache extends Driver
{
    public function execute(): Driver
    {
        $domain = $this->params['domain'] ?? $this->params['project']['domain'];
        $this->content = str_replace('{{DOMAIN}}', $domain, $this->params['template']);
        return $this;
    }
}
