<?php

declare(strict_types=1);

namespace generator\parser;

use generator\Cache;
use generator\helper\FileHelper;
use generator\node\Node;
use generator\processor\{ProcessorType, ProcessorFactory};

class Openapi extends Parser
{
    public function parse(): void
    {
        $project = null;
        foreach ($this->data_files as $f) {
            $data = FileHelper::readDataFromFile(ROOT_PATH . '/' . $f) ?: [];

            //根据获取的数据文件创建对象树
            $project = Node::create('Project', ['data' => $data]);
            if (!empty($project)) {
                $this->runProcessors();
            }
        }
    }

    private function runProcessors()
    {
        //遍历处理器并处理数据
        foreach (ProcessorType::cases() as $pt) {
            foreach ($this->processor_keys as $key) {
                if (strtolower($pt->name) === strtolower($key)) {
                    $processor = ProcessorFactory::create($pt);
                    $processor->process();
                }
            }
        }
    }
}
