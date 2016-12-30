<?php
namespace thinkbuilder\generator\profile;

use thinkbuilder\generator\Generator;

/**
 * Class Apache apache 虚拟主机配置文件生成器
 * @package thinkbuilder\generator\profile
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