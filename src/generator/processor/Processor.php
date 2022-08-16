<?php

declare(strict_types=1);

namespace generator\processor;

use generator\Cache;

/**
 * Class Processor 抽象处理程序
 * @package generator\processor
 */
abstract class Processor
{
    protected string $src_path = '';
    protected string $tar_path = '';

    public function setPaths(string $src_path, string $tar_path): void
    {
        $this->src_path = $src_path ?: '';
        $this->tar_path = $tar_path ?: '';
    }

    //子类需要实现的处理方法
    abstract public function process();
}
