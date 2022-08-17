<?php

declare(strict_types=1);

namespace generator\processor;

use generator\helper\FileHelper;

class Backend extends Processor
{
    public function process(): void
    {
        //克隆基本目录结构和代码
        if($this->res !== null and $this->res instanceof Resource) {
            $this->res->clone();
        }
    }
}
