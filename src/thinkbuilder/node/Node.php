<?php
namespace thinkbuilder\node;


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
        'trait',
        'field',
        'relation'
    ];
    //当前节点类型，来自于 $types
    protected $type = 0;
    //节点名称，英文小写
    protected $name = '';
    //节点说明，中文
    protected $caption = '';
    //节点路径
    protected $path = '';

    protected $data = [];
    protected $config = [];
    protected $paths = [];
    protected $namespace = '';
    protected $parent_namespace = '';

    protected $children = [];

    /**
     * 根据类型与参数创建Node实例的工厂方法
     * @param string $type
     * @param array $params
     * @return Node
     */
    final public static function create($type = '', $params = [])
    {
        $class = 'thinkbuilder\\node\\' . $type;
        $obj = (class_exists($class)) ? new $class() : null;
        if ($obj instanceof Node) $obj->init($params);
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

        //如果存在 $this->data 则再遍历一次 $this->data 通过 $this->data 设置属性
        if (property_exists($this, 'data')) {
            foreach ($this->data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
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
            $children = $this->data[$type . 's'];
            foreach ($children as $child) {
                //如果字节点类型并非是数组，则视为引用已经设定的数据
                if (!is_array($child)) {
                    $_file = PACKAGE_PATH . "/$type/" . $child . '.php';
                    if (is_file($_file)) {
                        $child = require $_file;
                    } else {
                        continue;
                    }
                }
                $this->children[] = Node::create(ucfirst($type),
                    [
                        'data' => $child,
                        'config' => $this->config,
                        'paths' => $this->paths,
                        'parent_namespace' => $this->namespace
                    ]
                );
            }

            //遍历子节点，并触发可以递归的处理方法
            foreach ((function ($children) {
                foreach ($children as $child) {
                    yield $child;
                }
            })($this->children) as $child) {
                if ($child instanceof Node) {
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
        $this->path = $this->paths['application'] . str_replace('\\', '/', $this->namespace);
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