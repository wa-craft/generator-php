<?php

/**
 * Class Node 节点类，所有节点对象的夫类
 */
class Node
{
    static public $types = [
        'PROJECT',
        'APPLICATION',
        'MODULE',
        'CONTROLLER',
        'MODEL',
        'VIEW',
        'ACTION',
        'FIELD'
    ];
    //节点类型，来自于 $types
    protected $type = 0;
    //节点名称，英文小写
    protected $name = '';
    //节点说明，中文
    protected $caption = '';

    /**
     * 根据类型与参数创建Node实例的工厂方法
     * @param int $type
     * @param array $params
     * @return Node
     */
    public static function getInstance($type = 0, $params = [])
    {
        $instance = null;
        $type = (count(self::$types) < $type) ? $type : 0;
        $class_name = ucfirst(strtolower(self::$types[$type]));
        if (class_exists($class_name)) {
            $instance = new $class_name();
            if (count($params) !== 0) {
                $instance->init($params);
            }
        }
        return $instance;
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
    }

    /**
     * 进行处理的主函数
     */
    public function process()
    {
        //创建目录
        //拷贝文件
        //处理模板文件
        //写入文件
    }

    /**
     * 根据节点属性创建相关目录
     */
    protected function makeDir()
    {

    }

    /**
     * 根据节点属性拷贝相关文件
     * @param array $files
     */
    protected function copyFiles($files = [])
    {

    }

    /**
     * 根据节点属性生成HTML内容
     */
    protected function generateHTML()
    {

    }

    /**
     * 根据节点属性生成SQL内容
     */
    protected function generateSQL()
    {

    }

    /**
     * 根据节点属性生成PHP内容
     */
    protected function generatePHP()
    {

    }

    /**
     * 根据节点属性生成其他内容
     */
    protected function generateMISC()
    {

    }
}