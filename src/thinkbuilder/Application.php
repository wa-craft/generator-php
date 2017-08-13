<?php

namespace thinkbuilder;

use thinkbuilder\helper\FileHelper;
use thinkbuilder\node\Node;

/**
 * Class Builder 构建程序
 */
class Application
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
        'console' => __DIR__ . '/deploy/' . CONSOLE_PATH,
        'assets' => __DIR__ . '/assets'
    ];

    //数据
    private $data = [];

    public function __construct($params = [])
    {
        if (key_exists('config', $params)) $this->setConfigFromFile($params['config']);
        if (key_exists('data', $params)) $this->setDataFromFile($params['data']);
        if (key_exists('target', $params)) $this->paths['target'] = $params['target'];
        if (key_exists('assets', $params)) $this->paths['assets'] = $params['assets'];
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
        if (!preg_match('/\.php$/', $file)) $file .= '.php';
        $this->data = require $file;
    }

    /**
     * 创建基本目录结构
     */
    protected function makeBaseDirectories()
    {
        $this->paths = array_merge($this->paths, [
            'application' => $this->paths['target'] . '/' . APP_PATH,
            'database' => $this->paths['target'] . '/' . DBFILE_PATH,
            'profile' => $this->paths['target'] . '/' . PROFILE_PATH,
            'public' => $this->paths['target'] . '/' . PUB_PATH,
            'console' => $this->paths['target'] . '/' . CONSOLE_PATH
        ]);

        FileHelper::mkdirs($this->paths);
    }

    /**
     * 从主题共用目录拷贝资源文件
     * TODO 将拷贝资源文件的工作移动到 module 中执行，并拷贝到 public/assets/themes/{{theme}}目录下
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
        $this->makeBaseDirectories();
        FileHelper::copyFiles(ASSETS_PATH . '/base', $this->paths['target']);

        /* 拷贝资源文件 */
        $this->copyAssets();

        /* 装载默认设置并进行缓存 */
        $cache = Cache::getInstance();
        $cache->set('defaults', $this->config['defaults']);
        $cache->set('config', $this->config);
        $cache->set('paths', $this->paths);

        //TODO 使用不同的方式获取数据
        $project = Node::create('Project', ['data' => $this->data]);
        $project->process();
        echo "ThinkForge Builder, Version: " . VERSION . PHP_EOL;
    }
}
