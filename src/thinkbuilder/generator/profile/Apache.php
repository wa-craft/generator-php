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
        $this->content = str_replace('{{DOMAIN}}', $this->params['project']['domain'], $this->params['template']);
        return $this;
    }
}