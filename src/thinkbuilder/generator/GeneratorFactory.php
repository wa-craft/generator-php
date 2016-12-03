<?php
namespace thinkbuilder\generator;

/**
 * Class GeneratorFactory 创建生成器的工厂方法
 * @package thinkbuilder\generator
 */
class GeneratorFactory
{
    /**
     * 根据给出的类型，创建生成器
     * @param string $type
     * @param array $params
     * @return null|Generator
     */
    public static function create($type = 'PHP', $params = [])
    {
        $class = 'thinkbuilder\\generator\\' . $type . 'Generator';
        $obj = (class_exists($class)) ? new $class() : null;
        if ($obj instanceof Generator) $obj->setParams($params);
        return $obj;
    }
}