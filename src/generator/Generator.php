<?php

namespace generator;

use generator\helper\{FileHelper,ClassHelper};
use generator\node\Node;
use generator\parser\ParserFactory;

/**
 * Class Builder 构建程序
 * TODO 生成内存中的树状数据结构，再调用每种生成器进行处理
 * TODO 在view中根据每个页面的需求，生成独立调用的js文件
 * TODO 使用 sqlite 作为存储引擎
 * TODO 拆分纯粹的命令行与B/S架构的运行命令（提供SERVE命令）
 * TODO 提供默认的控制器/模型/校验器，用以扩展
 * TODO 完善自定义的校验规则
 * TODO 提供 vue 脚手架
 */
class Generator
{
    //配置参数
    private array $config = [];
    //系统基本路径
    private array $paths = [];
    //项目配置
    private array $project = [];
    //数据
    private array $data = [];

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
     * 从模板目录拷贝资源文件
     */
    protected function copyResources(): void
    {
        $src = '';
        $tar = '';

        $iters = ['backend', 'frontend', 'commandline'];

        foreach ($iters as $v) {
            if (!empty($this->project[$v])) {
                $src = RESOURCE_PATH . "/" . $v . "/" . $this->project[$v] . '/src';
                $tar = $this->paths[$v] ?: '';
                if ($tar !== '') {
                    FileHelper::copyFiles($src, $tar);
                }
            }
        }
    }

    /**
     * 创建项目文件的主方法
     *
     * 执行流程：
     *  创建基本目录结构
     *  拷贝全局资源文件
     *  装载配置信息并进行缓存
     *  获取数据并缓存
     *  注册预处理器
     *  执行预处理，并生成任务
     *  执行任务
     */
    public function run(): void
    {
        /* 创建基本目录 */
        FileHelper::mkdir(($this->config['target_path'] ?: './deploy'), true);

        /* 装载默认设置并进行缓存 */
        $cache = Cache::getInstance();
        $cache->set('config', $this->config);
        $cache->set('paths', $this->paths);
        $cache->set('project', $this->project);

        //实例化 parser
        $parser_name = $this->project['parser'] ?: 'legacy';
        $parser = ParserFactory::createByName($parser_name);
        $parser->parse();

        echo "wa-craft/generator-php, Version: " . VERSION . PHP_EOL;
    }
}
