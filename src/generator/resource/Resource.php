<?php

namespace generator\resource;

/**
 * 抽象资源管理器
 */
abstract class Resource
{
    //资源文件源地址
    protected string $sourcePath = '';
    //资源文件目的地址
    protected string $targetPath = '';
    //规则配置
    protected array $rules = [];

    public function setSourcePath(string $path): void
    {
        $this->sourcePath = $path;
    }

    public function setTargetPath(string $path): void
    {
        $this->targetPath = $path;
    }
}
