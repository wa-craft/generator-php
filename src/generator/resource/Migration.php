<?php

declare(strict_types=1);

namespace generator\resource;

class Migration extends Resource
{
    public function process(): void
    {
        echo "processing schema" . PHP_EOL;
    }
}
