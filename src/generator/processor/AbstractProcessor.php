<?php

declare(strict_types=1);

namespace generator\processor;

/**
 * Class AbstractProcessor 抽象处理程序
 * @package generator\processor
 */
abstract class AbstractProcessor
{
    abstract public function process();
}
