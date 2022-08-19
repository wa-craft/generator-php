<?php

declare(strict_types=1);

namespace generator\resource;

class Document extends Resource
{
    public function process(): void
    {
        echo "processing document" . PHP_EOL;
    }
}
