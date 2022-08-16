<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\FileHelper;
use generator\node\Node;

class Apifox extends AbstractParser
{
    public function parse(): void
    {
        $project = null;
        foreach ($this->data_files as $f) {
            $data = FileHelper::readDataFromFile(ROOT_PATH . '/' . $f) ?: [];
            $project = Node::create('Project', ['data' => $data]);
            if (!empty($project)) {
                $project->process();
            }
        }
    }
}
