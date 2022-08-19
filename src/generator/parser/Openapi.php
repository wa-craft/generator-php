<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\FileHelper;
use generator\parser\legacy\Node;
use generator\processor\{ProcessorFactory};
use generator\resource\ResourceType;
use generator\template\TemplateFactory;
use generator\template\TemplateType;

class Openapi extends Parser
{
    public function parse(): void
    {
        $project = null;
        foreach ($this->data_files as $f) {
            $data = FileHelper::readDataFromFile(ROOT_PATH . '/' . $f) ?: [];

            //处理数据
            $paths = $data['paths'] ?: [];
            foreach ($paths as $path_key => $path) {
                $path_array = explode('/', $path_key);
                if (is_array($path_array)) {
                    $params['action'] = array_pop($path_array);
                    $params['controller'] = array_pop($path_array);
                    $params['path'] = implode('/', $path_array);
                }
            }

            $components = $data['components']['schemas'] ?: [];
            foreach ($components as $component_key => $component) {
                //var_dump($component);
            }

            //根据获取的数据文件创建对象树
            $project = Node::create('Project', ['data' => $data]);
            if (!empty($project)) {
                $this->prepareData();
            }
        }
    }

    public function getParsedData(): array
    {
        // TODO: Implement getParsedData() method.
        return [];
    }

    protected function prepareData()
    {
    }
}
