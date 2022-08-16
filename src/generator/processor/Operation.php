<?php

declare(strict_types=1);

namespace generator\processor;

class Operation extends Processor
{
    public function process(): void
    {
        echo "processing operation" . PHP_EOL;
    }
}
