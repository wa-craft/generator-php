<?php

namespace generator\resource;

use generator\helper\FileHelper;

/**
 * 抽象资源管理器
 */
abstract class Resource
{
    //资源文件源地址
    protected string $sourcePath = '';
    //资源文件目的地址
    protected string $targetPath = '';
    //规则配置
    protected array $rules = [];
    protected array $templates = [];

    public function setSourcePath(string $path): void
    {
        $this->sourcePath = $path;
    }

    public function setTargetPath(string $path): void
    {
        $this->targetPath = $path;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @return void
     */
    public function fetchRules(): void
    {
        $file = $this->sourcePath . '/rule.yaml';
        $content = FileHelper::readDataFromFile($file) ?: [];
        if (!empty($content)) {
            $this->rules = array_key_exists('rules', $content) ? $content['rules'] : [];
            $this->templates = array_key_exists('templates', $content) ? $content['templates'] : [];
        }
    }
}
