<?php

declare(strict_types=1);

namespace generator\task;

use generator\Cache;

/**
 * 任务管理器
 */
final class TaskManager
{
    private array $tasks = [];
    private static ?TaskManager $instance = null;
    /**
     * 创建并返回实例
     * @return null|TaskManager
     */
    public static function getInstance(): TaskManager|null
    {
        if (self::$instance === null || !(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 添加一个任务
     * @param $task
     * @return void
     */
    public function addTask($task): void
    {
        if ($task instanceof Task) {
            $this->tasks[] = $task;
        }
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * 运行所有的任务
     * @return void
     */
    public function runTasks(): void
    {
        foreach ($this->tasks as $key => $task) {
            $task->execute();
        }
    }
}
