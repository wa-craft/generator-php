<?php
namespace thinkbuilder\generator\profile;

use thinkbuilder\generator\Generator;

class Nginx extends Generator
{
    public function generate(): Generator
    {
        $content = str_replace('{{DOMAIN}}', $this->params['project']['domain'], $this->params['template']);

        foreach ($this->params['project']['applications'] as $application) {
            $content_temp = "\t\t\trewrite ^(.*)$ /" . $application['portal'] . ".php/$1 last;" . PHP_EOL . "{{REWRITE_LOOP}}";
            $content = str_replace('{{REWRITE_LOOP}}', $content_temp, $content);
        }
        $this->content = str_replace("\n{{REWRITE_LOOP}}", '', $content);

        return $this;
    }
}