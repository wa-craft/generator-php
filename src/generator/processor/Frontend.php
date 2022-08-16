<?php

declare(strict_types=1);

namespace generator\processor;

class Frontend extends Processor
{
    public function process(): void
    {
        echo "processing frontend" . PHP_EOL;
    }
}
