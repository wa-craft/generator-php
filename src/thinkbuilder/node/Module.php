<?php
namespace thinkbuilder\node;

use thinkbuilder\Cache;
use thinkbuilder\generator\Generator;
use thinkbuilder\helper\FileHelper;
use thinkbuilder\helper\TemplateHelper;

/**
 * Class Module
 * @package thinkbuilder\node
 */
class Module extends Node
{
    //控制器列表
    public $controllers = [];
    //模式列表
    public $schemas = [];
    //模型列表
    public $models = [];
    //特性列表
    public $traitss = [];
    //校验器列表
    public $validates = [];
    //助手列表
    public $helpers = [];
    //行为列表
    public $behaviors = [];
    //视图列表
    public $views = [];
    //模块使用的主题
    public $theme = '';
    //默认模块下所有控制器的父控制器名称，会根据此名称自动生成默认控制器，并且模块下所有控制器继承自此控制器
    public $default_controller = '';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        //设置主题
        if ($this->theme !== '') Cache::getInstance()->set('theme', $this->theme);

        //调用的顺序必须 getAllModels在前
        $this->getAllModels();
        $this->processChildren('model');

        //生成控制器列表
        $this->getAllControllers();
        $this->processChildren('controller');
        //特性列表
        $this->processChildren('traits');

        //助手列表
        $this->processChildren('helper');

        //行为
        $this->processChildren('behavior');


        //校验器
        $this->getAllValidates();
        $this->processChildren('validate');

        $this->getAllViews();
        $this->processChildren('view');
        FileHelper::copyFiles(ASSETS_PATH . '/themes/' . (Cache::getInstance()->get('theme') ?? Cache::getInstance()->get('config')['defaults']['theme']) . '/layout', $this->path . '/view/layout');

        //处理模板 layout 文件
        //生成视图 footer
        Generator::create('html\\LayoutFooter', [
            'path' => $this->path . '/view/layout',
            'file_name' => 'footer.html',
            'template' => TemplateHelper::fetchTemplate('view_layout_footer')
        ])->generate()->writeToFile();

        //生成视图 header
        Generator::create('html\\LayoutHeader', [
            'path' => $this->path . '/view/layout',
            'file_name' => 'html_head.html',
            'caption' => $this->caption,
            'template' => TemplateHelper::fetchTemplate('view_layout_header')
        ])->generate()->writeToFile();
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\\' . $this->name;
        $this->data['namespace'] = $this->namespace;
    }

    /**
     * 根据 Model 创建默认操作 Model 的控制器
     */
    protected function getAllControllers()
    {
        $controllers = [];
        if (key_exists('schemas', $this->data)) {
            $schemas = $this->data['schemas'];
            foreach ($schemas as $schema) {
                $controllers[] = Node::create('controller',
                    [
                        'data' => [
                            'name' => $schema['name'],
                            'caption' => $schema['caption'],
                            'parent_controller' => $this->default_controller,
                            'actions' => [
                                ['name' => 'index', 'caption' => '列表'],
                                ['name' => 'add', 'caption' => '添加'],
                                ['name' => 'mod', 'caption' => '修改'],
                                ['name' => 'view', 'caption' => '查看']
                            ],
                            'fields' => $schema['fields'],
                            'relations' => $schema['relations'] ?? []
                        ],
                        'parent_namespace' => $this->parent_namespace
                    ]);
            }

            //增加默认的空控制器
            $controllers[] = Node::create('controller',
                [
                    'data' => [
                        'name' => 'Error',
                        'caption' => '空控制器',
                        'parent_controller' => '',
                        'actions' => [
                            ['name' => 'index', 'caption' => '入口', 'params' => 'Request $request']
                        ],
                        'relations' => []
                    ],
                    'parent_namespace' => $this->parent_namespace
                ]);
        }

        $this->controllers = array_merge($this->controllers, $controllers);
    }

    /**
     * 根据 schema 创建 models
     */
    protected function getAllModels()
    {
        $models = [];
        if (key_exists('schemas', $this->data)) {
            $schemas = $this->data['schemas'];
            foreach ($schemas as $schema) {
                $models[] = Node::create('Model',
                    [
                        'data' => [
                            'name' => $schema['name'],
                            'caption' => $schema['caption'],
                            'fields' => $schema['fields'],
                            'relations' => $schema['relations']?? [],
                            'autoWriteTimeStamp' => $schema['autoWriteTimeStamp'] ?? false
                        ],
                        'parent_namespace' => $this->parent_namespace
                    ]);
            }
        }
        $this->models = array_merge($this->models, $models);
    }

    /**
     * 根据控制器来创建视图
     */
    protected function getAllViews()
    {
        $views = [];
        foreach ($this->controllers as $controller) {
            $views[] = Node::create('view',
                [
                    'data' => [
                        'name' => $controller->name,
                        'caption' => $controller->caption,
                        'actions' => $controller->actions,
                        'fields' => $controller->fields,
                        'relations' => $controller->data['relations'] ?? [],
                        'parent_controller' => $controller->parent_controller,
                        'module_name' => $this->name,
                        'module_caption' => $this->caption
                    ],
                    'parent_namespace' => $this->parent_namespace
                ]);
        }

        $this->views = array_merge($this->views, $views);
    }

    /**
     * 通过模型来创建校验器
     */
    protected function getAllValidates()
    {
        $validates = [];
        $models = $this->models;
        foreach ($models as $model) {
            $validates[] = Node::create('validate',
                [
                    'data' => [
                        'name' => $model->name,
                        'caption' => $model->caption . '校验器',
                        'fields' => $model->fields
                    ],
                    'parent_namespace' => $this->parent_namespace
                ]);
        }
        $this->validates = array_merge($this->validates, $validates);
    }
}