<?php
namespace thinkbuilder\generator\misc;

use thinkbuilder\generator\Generator;

/**
 * Class Apache $public/.htaccess apache 目录配置文件生成器
 * @package thinkbuilder\generator\misc
 */
class Apache extends Generator
{
    public function generate(): Generator
    {
        $content = str_replace('{{DOMAIN}}', $this->params['project']['domain'], $this->params['template']);

        foreach ($this->params['project']['applications'] as $application) {
            $content_temp = "  RewriteRule ^(.*)$ " . $application['portal'] . ".php/$1 [QSA,PT,L]" . PHP_EOL . "{{REWRITE_LOOP}}";
            $content = str_replace('{{REWRITE_LOOP}}', $content_temp, $content);
        }
        $this->content = str_replace("\n{{REWRITE_LOOP}}", '', $content);

        return $this;
    }
}