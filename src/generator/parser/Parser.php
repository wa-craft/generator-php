<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Context;

/**
 * Class AbstractParser 抽象处理程序
 * 分析数据配置文件，并进行处理成需要执行的任务
 * @package generator\parser
 */
abstract class Parser
{
    protected array $data_files = [];

    public function __construct()
    {
        $context = Context::getInstance();

        //获取数据文件
        $project_data_files = ($context->get('project'))['data'] ?: [];
        if (!is_array($project_data_files)) {
            $project_data_files = [$project_data_files];
        }
        $this->data_files = $project_data_files;
    }

    /**
     * 对数据文件进行分析，设置路由和模型数组
     * @return void
     */
    abstract public function parse(): void;
}
