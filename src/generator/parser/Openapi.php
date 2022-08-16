<?php

declare(strict_types=1);

namespace generator\parser;

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
            $project = Node::create('Project', ['data' => $data]);
            if (!empty($project)) {
                //遍历并处理数据
                foreach (ProcessorType::cases() as $pt) {
                    $processor = ProcessorFactory::create($pt);
                    $processor->process();
                }
            }
        }
    }
}
