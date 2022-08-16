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

        //设置基本目录
        $root_path = $cache->get('config')["target_path"] ?: ROOT_PATH . '/deploy';
        $target_paths = $cache->get('project')["target"] ?: [];
        foreach ($target_paths as $k => $v) {
            $target_paths = array_merge($target_paths, [$k => $root_path . '/' . $v]);
            array_push($this->processor_keys, $k);
        }
        $cache->set('target_paths', $target_paths);

        //获取数据文件
        $project_data_files = ($cache->get('project'))['data'] ?: [];
        if (!is_array($project_data_files)) {
            $project_data_files = [$project_data_files];
        }
        $this->data_files = $project_data_files;
    }

    abstract public function parse();
}
