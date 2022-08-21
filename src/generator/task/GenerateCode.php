<?php

declare(strict_types=1);

namespace generator\task;

class GenerateCode extends Task
{
    private string $stereoType = '';
    public function __construct(array $params)
    {
        $this->stereoType = $params['stereoType'] ?: '';
    }
    public function execute()
    {
        echo $this->stereoType . PHP_EOL;
    }
}
