<?php

namespace generator\driver\misc;

use generator\driver\Driver;

/**
 * Class Apache $public/.htaccess apache 目录配置文件生成器
 * @package generator\driver\misc
 */
class Apache extends Driver
{
    public function execute(): Driver
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
