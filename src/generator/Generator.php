<?php

declare(strict_types=1);

namespace generator;

use generator\helper\FileHelper;
use generator\parser\ParserFactory;
use generator\resource\ResourceFactory;
use generator\resource\ResourceType;
use generator\task\TaskManager;

/**
 * Class Builder 构建程序
 */
class Generator
{
    //配置参数
    private array $config = [];
    //系统基本路径
    private array $paths = [];
    //项目配置
    private array $project = [];
    //数据缓存
    private ?Context $context = null;
    //任务管理
    private ?TaskManager $taskManager = null;

    public function __construct($params = [])
    {
        if (key_exists('config', $params)) {
            $this->setConfigFromFile($params['config']);
        }
        if (key_exists('project', $params)) {
            $this->setProjectFromFile($params['project']);
        }
        if (key_exists('target', $params)) {
            $this->paths['target'] = $params['target'];
        }

        $this->cache = Context::getInstance();
        $this->taskManager = TaskManager::getInstance();
    }

    /**
     * 通过数组设置项目配置信息
     * @param array $config
     */
    public function setConfig(array $config = []): void
    {
        $this->config = $config;
    }

    /**
     * 通过指定的文件名获取并设置项目配置信息
     * @param $file
     */
    public function setConfigFromFile($file): void
    {
        $this->config = FileHelper::readDataFromFile($file) ?: [];
    }

    /**
     * 设置数据
     * @param array $project_config
     */
    public function setProject(array $project_config = []): void
    {
        $this->project = $project_config;
    }

    /**
     * 通过文件读取并设置数据，如果给出的数据文件名称，并未以 .php 结尾，则自动添加文件后缀名 .php
     * @param $file
     */
    public function setProjectFromFile($file): void
    {
        $this->project = FileHelper::readDataFromFile($file) ?: [];
    }

    /**
     * 创建基本目录结构
     */
    protected function makeBaseDirectories(): void
    {
        $root_path = './deploy';
        foreach ($this->project['target'] as $k => $v) {
            if ($k === 'root') {
                //判断是否是/开始的绝对路径
                $$root_path = (stripos($v, '/') === 0)
                    ? $root_path = $v
                    : $root_path =  ROOT_PATH . '/' . $v;

                $this->paths = array_merge($this->paths, [$k => $root_path]);
            } else {
                $this->paths = array_merge($this->paths, [$k => $root_path . '/' . $v]);
            }
        }
        FileHelper::mkdirs($this->paths);
    }

    /**
     * 装载默认设置并进行缓存
     * @return void
     */
    private function setDefaultCache(): void
    {
        $this->cache->set('config', $this->config);
        $this->cache->set('paths', $this->paths);
        $this->cache->set('project', $this->project);
    }

    /**
     * 设置基本目录
     * @return void
     */
    private function setTargetPaths(): void
    {
        $root_path = $this->cache->get('config')["target_path"] ?: ROOT_PATH . '/deploy';
        $target_paths = $this->cache->get('project')["target"] ?: [];
        foreach ($target_paths as $k => $v) {
            $target_paths = array_merge($target_paths, [$k => $root_path . '/' . $v]);
        }
        $this->cache->set('target_paths', $target_paths);
    }

    /**
     * 通过项目配置文件中已经配置的项目内容实例化资源管理器
     * @return void
     */
    private function setResources(): void
    {
        $resources = [];
        foreach (ResourceType::cases() as $case) {
            $resName = '';
            if (array_key_exists(strtolower($case->name), $this->project)) {
                $resName = $this->project[strtolower($case->name)] ?: '';
            }

            if (!empty($resName)) {
                $obj = ResourceFactory::create($case);
                if (!empty($obj)) {
                    $resources[] = $obj;
                }
            }
        }
        $this->cache->set('resources', $resources);
    }

    /**
     * 实例化 parser，通过 parser 获取经过分析的数据
     * @return void
     */
    private function setParser(): void
    {
        $parser_name = $this->project['parser'] ?: 'openapi';
        $parser = ParserFactory::createByName($parser_name);
        $parser->parse();
        $this->cache->set('parser', $parser);
    }

    /**
     * 执行核心处理程序
     * 处理步骤：
     *  循环所有处理对象
     *  通过模板帮助程序处理生成模板
     * @return void
     */
    private function process(): void
    {
        $this->taskManager->runTasks();
    }

    /**
     * 创建项目文件的主方法
     *
     * 执行流程：
     *  创建基本目录结构
     *  获取缓存对象，并设置全局配置信息、基本路径信息、项目配置信息
     *  通过配置文件创建资源对象
     *  通过配置文件创建分析器
     *
     *  通过资源管理器获取模板规则
     *  通过分析器获取处理对象列表
     *  通过规则、对象列表、模板列表生成模板代码
     */
    public function run(): void
    {
        /* 创建基本目录 */
        FileHelper::mkdir(($this->config['target_path'] ?: './deploy'), true);

        //装载默认设置并进行缓存
        $this->setDefaultCache();

        //设置基本目录
        $this->setTargetPaths();

        //通过项目配置文件中已经配置的项目内容实例化资源管理器
        $this->setResources();

        //实例化 parser，通过 parser 获取经过分析的数据
        $this->setParser();

        //处理数据
        $this->process();

        echo "wa-craft/generator-php, Version: " . VERSION . PHP_EOL;
    }
}
