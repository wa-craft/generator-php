<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\FileHelper;

class Openapi extends Parser
{
    public function parse(): void
    {
        $project = null;
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
            $schemas = $data['components']['schemas'] ?: [];
            foreach ($schemas as $key => $schema) {
                var_dump($schema);
            }

            //根据获取的数据文件创建对象树
        }
    }
}
