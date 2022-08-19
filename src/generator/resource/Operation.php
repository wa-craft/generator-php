<?php

declare(strict_types=1);

namespace generator\resource;

class Operation extends Resource
{
    public function process(): void
    {
        echo "processing operation" . PHP_EOL;
    }
}
