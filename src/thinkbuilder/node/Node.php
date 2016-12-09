<?php
namespace thinkbuilder\node;

use thinkbuilder\Cache;

/**
 * Class Node 节点类，所有节点对象的父类
 * @package thinkbuilder\node
 */
abstract class Node
{
    //节点类型
    public static $types = [
        'project',
        'application',
        'module',
        'model',
        'controller',
        'validate',
        'view',
        'traits',
        'helper',
        'behavior',
        'field',
        'relation'
    ];
    //当前节点类型，来自于 $types
    public $type = '';
    //节点名称，英文小写
    public $name = '';
    //节点说明，中文
    public $caption = '';
    //节点路径
    public $path = '';

    protected $data = [];
    protected $namespace = '';
    protected $parent_namespace = '';

    //缓存的代码文本
    public $html = '';
    public $code = '';
    public $sql = '';
    public $misc = '';
    public $js = '';

    /**
     * 根据类型与参数创建Node实例的工厂方法
     * @param string $type
     * @param array $params
     * @return Node
     */
    final public static function create($type = '', $params = [])
    {
        $class = 'thinkbuilder\\node\\' . ucfirst($type);
        $obj = (class_exists($class)) ? new $class() : null;
        if ($obj instanceof Node) {
            $obj->type = strtolower($type);
            $obj->init($params);
        }
        return $obj;
    }

    /**
     * 根据参数动态匹配赋值类的属性
     * @param array $params
     */
    public function init($params = [])
    {
        foreach ($params as $key => $param) {
            if (property_exists($this, $key)) {
                $this->$key = $param;
            }
        }

        //TODO 此处可能存在性能问题
        //如果存在 $this->data 则再遍历一次 $this->data 通过 $this->data 设置属性
        if (property_exists($this, 'data')) {
            foreach ($this->data as $key => $value) {
                if (property_exists($this, $key)) {
                    //如果属性声明是数组，而数据中是字符串，则寻找相关的预定义内容
                    if (is_array($this->$key) && is_string($value)) {
                        $_file = PACKAGE_PATH . "/{$this->type}/" . $value . '.php';
                        if (is_file($_file)) {
                            $value = require $_file;
                        } else {
                            continue;
                        }
                    }

                    //如果值是数组，则创建子节点对象
                    if (is_array($value) && $key != 'data') {
                        $list = [];
                        foreach ($value as $item) {
                            if (is_array($item)) $list[] = Node::create(ucfirst(substr($key, 0, strlen($key) - 1)), ['data' => $item, 'parent_namespace' => $this->namespace]);
                        }
                        $this->$key = $list;
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }
    }

    /**
     * 根据类型处理子类数据
     * @param string $type
     */
    public function processChildren($type = '')
    {
        if (in_array($type, Node::$types)) {
            //处理生成子节点
            $property = $type . 's';
            $children = $this->$property;

            //遍历子节点，并触发可以递归的处理方法
            foreach ((function ($children) {
                foreach ($children as $child) {
                    yield $child;
                }
            })($children) as $child) {
                if ($child instanceof Node) {
                    $child->parent_namespace = $this->namespace;
                    $child->setNameSpace();
                    $child->setPathByNamespace();
                    $child->process();
                }
            }
        }
    }

    /**
     * 通过命名空间设置数据对应的操作目录
     */
    final public function setPathByNamespace()
    {
        $this->path = Cache::getInstance()->get('paths')['application'] . '/' . str_replace('\\', '/', $this->namespace);
    }

    /**
     * 进行处理的主函数，所有节点类都必须扩展
     */
    abstract public function process();

    /**
     * 设置命名空间
     */
    abstract public function setNameSpace();
}