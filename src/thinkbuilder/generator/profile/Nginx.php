<?php
namespace thinkbuilder\generator\profile;

use thinkbuilder\generator\Generator;

/**
 * Class Nginx nginx 虚拟主机配置文件生成器
 * @package thinkbuilder\generator\profile
 */
class Nginx extends Generator
{
    public function generate(): Generator
    {
        $domain = $this->params['domain'] ?? $this->params['project']['domain'];
        $content = str_replace('{{DOMAIN}}', $domain, $this->params['template']);

        foreach ($this->params['project']['applications'] as $application) {
            $content_temp = "\t\t\trewrite ^(.*)$ /" . $application['portal'] . ".php/$1 last;" . PHP_EOL . "{{REWRITE_LOOP}}";
            $content = str_replace('{{REWRITE_LOOP}}', $content_temp, $content);
        }
        $this->content = str_replace("\n{{REWRITE_LOOP}}", '', $content);

        return $this;
    }
}