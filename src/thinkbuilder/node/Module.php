<?php
namespace thinkbuilder\node;

use thinkbuilder\Cache;
use thinkbuilder\helper\FileHelper;

/**
 * Class Module
 * @package thinkbuilder\node
 */
class Module extends Node
{
    //模块下的控制器
    public $controllers = [];
    //模块下的模型
    public $models = [];
    //模块下的特性
    public $traitss = [];
    //模块下的校验器
    public $validates = [];
    //模块下的视图
    public $views = [];
    //默认模块下所有控制器的父控制器名称，会根据此名称自动生成默认控制器，并且模块下所有控制器继承自此控制器
    public $default_parent_controller = '';

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        //调用的顺序必须 getAllControllers 在前
        $this->getAllControllers();
        $this->processChildren('controller');

        $this->processChildren('traits');
        $this->processChildren('model');

        //校验器
        $this->getAllValidates();
        $this->processChildren('validate');

        $this->getAllViews();
        $this->processChildren('view');
        FileHelper::copyFiles(ASSETS_PATH . '/themes/' . Cache::getInstance()->get('config')['theme'] . '/layout', $this->path . '/view/layout');
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
        if (key_exists('models', $this->data)) {
            $models = $this->data['models'];
            foreach ($models as $model) {
                $controllers[] = Node::create('controller',
                    [
                        'data' => [
                            'name' => $model['name'],
                            'caption' => $model['caption'] . '控制器',
                            'parent_controller' => $this->default_parent_controller,
                            'actions' => [
                                ['name' => 'index', 'caption' => '索引方法'],
                                ['name' => 'add', 'caption' => '添加方法'],
                                ['name' => 'mod', 'caption' => '修改方法']
                            ],
                            'fields' => $model['fields']
                        ],
                        'parent_namespace' => $this->parent_namespace
                    ]);
            }
        }

        $this->controllers = array_merge($this->controllers, $controllers);
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
                        'caption' => $controller->caption . '视图',
                        'actions' => $controller->actions,
                        'fields' => $controller->fields,
                        'parent_controller' => $controller->parent_controller
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
        $models = $this->data['models'];
        foreach ($models as $model) {
            $validates[] = Node::create('validate',
                [
                    'data' => [
                        'name' => $model['name'],
                        'caption' => $model['caption'] . '校验器',
                        'fields' => $model['fields']
                    ],
                    'parent_namespace' => $this->parent_namespace
                ]);
        }
        $this->validates = array_merge($this->validates, $validates);
    }
}