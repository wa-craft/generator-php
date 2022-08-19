<?php

declare(strict_types=1);

namespace generator\task;

/**
 * 任务管理器
 */
final class TaskManager
{
    private array $tasks = [];

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
