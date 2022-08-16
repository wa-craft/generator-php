<?php

declare(strict_types=1);

namespace generator\processor;

class Document extends Processor
{
    public function process(): void
    {
        echo "processing document" . PHP_EOL;
    }
}
