<?php

declare(strict_types=1);

namespace generator\processor;

class Backend extends Processor
{
    public function process(): void
    {
        echo "processing backend" . PHP_EOL;
    }
}
