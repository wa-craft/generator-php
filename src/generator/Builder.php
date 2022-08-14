<?php

namespace generator;

use generator\helper\FileHelper;
use generator\node\Node;

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
class Builder
{
    //配置参数
    private $config = [];
    //系统基本路径
    private $paths = [
        'target' => __DIR__ . './deploy',
        'application' => __DIR__ . '/deploy' . '/' . APP_PATH,
        'database' => __DIR__ . '/deploy/' . DBFILE_PATH,
        'profile' => __DIR__ . '/deploy/' . PROFILE_PATH,
        'public' => __DIR__ . '/deploy/' . PUB_PATH,
        'config' => __DIR__ . '/deploy/' . CONFIG_PATH,
        'view' => __DIR__ . '/deploy/' . VIEW_PATH,
        'resource' => __DIR__ . '/resource'
    ];

    //数据
    private $data = [];

    public function __construct($params = [])
    {
        if (key_exists('config', $params)) $this->setConfigFromFile($params['config']);
        if (key_exists('data', $params)) $this->setDataFromFile($params['data']);
        if (key_exists('target', $params)) $this->paths['target'] = $params['target'];
    }

    /**
     * 通过数组设置项目配置信息
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->config = $config;
    }

    /**
     * 通过指定的文件名获取并设置项目配置信息
     * @param $file
     */
    public function setConfigFromFile($file)
    {
        $this->config = require $file;
    }

    /**
     * 设置数据
     * @param array $data
     */
    public function setData($data = [])
    {
        $this->data = $data;
    }

    /**
     * 通过文件读取并设置数据，如果给出的数据文件名称，并未以 .php 结尾，则自动添加文件后缀名 .php
     * @param $file
     */
    public function setDataFromFile($file)
    {
        
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        echo "file:" . $file . "," . $ext;
        switch(strtolower($ext)) {
            case 'yml':
            case 'yaml':
                $this->data = yaml_parse_file($file);
                break;
            case 'php': 
            default:
            $this->data = require $file;
        }
    }

    /**
     * 创建基本目录结构
     */
    protected function makeBaseDirectories()
    {
        $root_path = './deploy';
        foreach($this->data['target'] as $k => $v) {
            if($k === 'root') {
                //判断是否是/开始的绝对路径
                $$root_path = (stripos($v, '/') === 0)
                    ? $root_path = $v
                    : $root_path =  __DIR__ . '/../../' . $v;
                
                $this->paths = array_merge($this->paths, [$k => $root_path]);
            } else {
                $this->paths = array_merge($this->paths, [$k => $root_path . '/' . $v]);
            }
        }
        FileHelper::mkdirs($this->paths);
    }

    /**
     * 从主题共用目录拷贝资源文件
     */
    protected function copyAssets()
    {
        $src = ASSETS_PATH . '/themes/share/assets';
        $tar = $this->paths['public'] . '/assets';
        FileHelper::copyFiles($src, $tar);
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
    public function run()
    {
        /* 创建基本目录 */
        $this->makeBaseDirectories(); exit;
        FileHelper::copyFiles(ASSETS_PATH . '/base', $this->paths['target']);

        /* 拷贝资源文件 */
        $this->copyAssets();

        /* 装载默认设置并进行缓存 */
        $cache = Cache::getInstance();
        $cache->set('defaults', $this->config['defaults']);
        $cache->set('config', $this->config);
        $cache->set('paths', $this->paths);

        $project = Node::create('Project', ['data' => $this->data]);
        $project->process();
        echo "ThinkForge Builder, Version: " . VERSION . PHP_EOL;
    }
}
