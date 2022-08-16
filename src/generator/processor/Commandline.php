<?php

declare(strict_types=1);

namespace generator\processor;

class Commandline extends Processor
{
    public function process(): void
    {
        echo "processing commandline" . PHP_EOL;
    }
}
