<?php

declare(strict_types=1);

namespace generator\resource;

class Backend extends Resource
{
    public function process(): void
    {
        //克隆基本目录结构和代码
        if ($this->res !== null and $this->res instanceof Resource) {
            $this->res->clone();

            foreach ($this->res->getTemplates() as $k => $template) {
                echo $k . PHP_EOL;
                $template->writeToFile();
            }
        }
    }
}
