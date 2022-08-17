<?php

namespace generator\template;

/**
 * 抽象模板对象
 */
abstract class Template
{
    protected string $stub = '';
    protected string $path = '';

    public function setFilePath(string $path): void
    {
        $this->path = $path;
    }
}
