<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Cache;

/**
 * Class AbstractParser 抽象处理程序
 * @package generator\parser
 */
abstract class AbstractParser
{
    protected array $data_files = [];

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

    abstract public function parse();
}
