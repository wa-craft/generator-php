<?php

declare(strict_types=1);

namespace generator\processor;

class Migration extends Processor
{
    public function process(): void
    {
        echo "processing schema" . PHP_EOL;
    }
}
