<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\FileHelper;
use generator\parser\legacy\Node;
use generator\processor\{ProcessorFactory, ProcessorType};
use generator\template\TemplateFactory;
use generator\template\TemplateType;

class Openapi extends Parser
{
    public function parse(): void
    {
        $project = null;
        $templates = [];
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

                    $template = TemplateFactory::create(TemplateType::Route, $params);
                    $templates[] = $template;
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

        $this->runProcessors(['templates' => $templates]);
    }

    /**
     * 准备数据
     * @return void
     */
    protected function prepareData(): void
    {
    }

    protected function runProcessors($params)
    {
        //遍历处理器并处理数据
        foreach (ProcessorType::cases() as $pt) {
            foreach ($this->processor_keys as $key) {
                if (strtolower($pt->name) === strtolower($key)) {
                    $processor = ProcessorFactory::create($pt, $params);
                    $processor->process();
                }
            }
        }
    }
}
