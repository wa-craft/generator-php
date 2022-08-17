<?php

declare(strict_types=1);

namespace generator\processor;

use generator\helper\FileHelper;

/**
 * 用于管理处理器资源的对象
 */
class Resource
{
    //资源文件源地址
    private string $src_path = '';
    //资源文件目的地址
    private string $tar_path = '';
    //规则配置
    private array $rules = [];

    public function __construct($params)
    {
        $this->src_path = $params['source'] ?: '';
        $this->tar_path = $params['target'] ?: '';

        //配置rule
        $this->rules = $this->getRulesFromeFile();
    }

    public function getSourcePath(): string
    {
        return $this->src_path;
    }

    public function getTargetPath(): string
    {
        return $this->tar_path;
    }

    public function getRules(): array
    {
        if (empty($this->rules)) {
            $this->rules = $this->getRulesFromeFile();
        }

        return $this->rules;
    }

    public function getRulesFromeFile(): array
    {
        $rules = [];
        if (!empty($this->src_path)) {
            $file = $this->src_path . '/rule.yaml';
            $content = FileHelper::readDataFromFile($file);
            $rules = $content['rules'] ?: [];
        } else {
            echo "WARNING: CANNOT find the rule file!";
        }

        return $rules;
    }

    /**
     * 从源地址克隆拷贝
     */
    public function clone(): void
    {
        if (!empty($this->src_path) && !empty($this->tar_path)) {
            FileHelper::copyFiles($this->src_path . '/src', $this->tar_path);
        } else {
            echo "WARNING: CANNOT find the source path or the target path of resouce!" . PHP_EOL;
        }
    }
}
