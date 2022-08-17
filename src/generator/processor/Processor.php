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
    public ?Resource $res = null;

    //子类需要实现的处理方法
    abstract public function process();
}
