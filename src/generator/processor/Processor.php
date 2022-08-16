<?php

declare(strict_types=1);

namespace generator\processor;

/**
 * Class Processor 抽象处理程序
 * @package generator\processor
 */
abstract class Processor
{
    abstract public function process();
}
