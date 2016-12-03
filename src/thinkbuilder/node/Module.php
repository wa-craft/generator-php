<?php
namespace thinkbuilder\node;

use thinkbuilder\helper\FileHelper;

/**
 * Class Module
 * @package thinkbuilder\node
 */
class Module extends Node
{
    //模块下的控制器
    protected $controllers = [];
    //模块下的模型
    protected $models = [];
    //模块下的特性
    protected $traitss = [];
    //模块下的校验器
    protected $validates = [];
    //模块下的视图
    protected $views = [];
    //默认模块下所有控制器的父控制器名称，会根据此名称自动生成默认控制器，并且模块下所有控制器继承自此控制器
    protected $parent_controller = '';

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
                            'parent_controller' => 'TestController',
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

    public function process()
    {
        //创建目录
        FileHelper::mkdir($this->path);

        $this->getAllControllers();

        $this->processChildren('controller');
        $this->processChildren('traits');
        $this->processChildren('model');
    }

    public function setNameSpace()
    {
        $this->namespace = $this->parent_namespace . '\\' . $this->name;
        $this->data['namespace'] = $this->namespace;
    }
}