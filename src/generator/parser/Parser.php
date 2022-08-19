<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Cache;
use generator\helper\FileHelper;

/**
 * Class AbstractParser 抽象处理程序
 * @package generator\parser
 */
abstract class Parser
{
    protected array $data_files = [];
    protected array $processor_keys = [];

    public function __construct()
    {
        $cache = Cache::getInstance();

        //获取数据文件
        $project_data_files = ($cache->get('project'))['data'] ?: [];
        if (!is_array($project_data_files)) {
            $project_data_files = [$project_data_files];
        }
        $this->data_files = $project_data_files;
    }

    abstract public function getParsedData(): array;
    abstract public function parse();
}
