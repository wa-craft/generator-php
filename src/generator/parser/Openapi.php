<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Cache;
use generator\helper\FileHelper;

class Openapi extends Parser
{
    public function parse(): void
    {
        //获取规则
        $cache = Cache::getInstance();
        $resources = $cache->get('resources') ?: [];
        $tasks = [];
        foreach ($resources as $resource) {
            $rules = $resource->getRules();
            $templates = $resource->getTemplates();

            foreach ($this->data_files as $f) {
                $data = FileHelper::readDataFromFile(ROOT_PATH . '/' . $f) ?: [];

                //处理 paths 数据
                $paths = $data['paths'] ?: [];
                foreach ($paths as $path_key => $path) {
                    $path_array = explode('/', $path_key);
                    if (is_array($path_array)) {
                        $params['action'] = array_pop($path_array);
                        $params['controller'] = array_pop($path_array);
                        $params['path'] = implode('/', $path_array);
                    }
                }

                //处理 components 数据
                $components = $data['components'] ?: [];
                if (!empty($components)) {
                    $schemas = $components['schemas'] ?: [];
                    foreach ($schemas as $key => $schema) {
                        //创建 schema 对象
                        //读取rule/rules数据，遍历schema对象需要生成的模板名称
                        $schema_targets = array_key_exists('schema', $rules) ? $rules['schema'] : [];
                        foreach ($schema_targets as $target) {
                            $tasks[] = [$target, $schema];
                        }
                    }
                }
            }
        }

        $cache->set('tasks', $tasks);
    }
}
