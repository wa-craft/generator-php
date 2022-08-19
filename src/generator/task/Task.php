<?php

declare(strict_types=1);

namespace generator\task;

/**
 * 抽象任务类
 */
abstract class Task
{
    abstract public function execute();
}
