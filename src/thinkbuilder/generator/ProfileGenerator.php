<?php
namespace thinkbuilder\generator;

/**
 * Class ProfileGenerator apache | nginx 配置文件生成器
 * @package thinkbuilder\generator
 */
class ProfileGenerator extends Generator
{
    public function generate(): Generator
    {
        $content = str_replace('{{DOMAIN}}', $this->params['project']['domain'], $this->params['template']);
        switch ($this->params['type']) {
            case 'nginx':

                foreach ($this->params['project']['applications'] as $application) {
                    $content_temp = "\t\t\trewrite ^(.*)$ /" . $application['portal'] . ".php/$1 last;" . PHP_EOL . "{{REWRITE_LOOP}}";
                    $content = str_replace('{{REWRITE_LOOP}}', $content_temp, $content);
                }
                $this->content = str_replace("\n{{REWRITE_LOOP}}", '', $content);
                break;
            case 'apache':
                foreach ($this->params['project']['applications'] as $application) {
                    $content_temp = "  RewriteRule ^(.*)$ " . $application['portal'] . ".php/$1 [QSA,PT,L]" . PHP_EOL . "{{REWRITE_LOOP}}";
                    $content = str_replace('{{REWRITE_LOOP}}', $content_temp, $content);
                }
                $this->content = str_replace("\n{{REWRITE_LOOP}}", '', $content);
                break;
        }

        return $this;
    }
}