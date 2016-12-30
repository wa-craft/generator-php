<?php
namespace thinkbuilder\helper;

/**
 * Class ClassName 类名助手类
 */
class ClassHelper
{
    /**
     * 将类的驼峰式命名方式转换为下划线命名方式
     * @param $name
     * @return string
     */
    public static function camelToDash($name)
    {
        $s = '';
        $array = [];
        for ($i = 0; $i < strlen($name); $i++) {
            if ($name[$i] == strtolower($name[$i])) {
                $array[] = $name[$i];
            } else {
                if ($i > 0) {
                    $array[] = '_';
                }
                $array[] = strtolower($name[$i]);
            }
        }

        $s .= implode('', $array);

        return $s;
    }

    /**
     * 将类的下划线命名方式转换为驼峰式命名方式
     * @param $name
     * @return string
     */
    public static function dashToCamel($name)
    {
        $s = '';
        $names = explode('_', $name);
        foreach ($names as $n) {
            $s .= ucfirst($n);
        }
        return $s;
    }

    /**
     * 将模型的驼峰式命名方式转换为数据表的下划线命名方式
     * @param $name
     * @param $table_prefix
     * @return string
     */
    public static function convertToTableName($name, $table_prefix = '')
    {
        $s = $table_prefix == '' ? ClassHelper::camelToDash($name) : $table_prefix . '_' . ClassHelper::camelToDash($name);

        return $s;
    }

    /**
     * 将数据表的下划线命名方式转换为模型的驼峰式命名方式
     * @param string $name
     * @param string $table_prefix
     * @return string
     */
    public static function convertFromTableName($name, $table_prefix = '')
    {
        $name = str_replace($table_prefix, '', $name);
        return ClassHelper::dashToCamel($name);
    }

    /**
     * 将 namespace 转换成表名前缀
     * 如果以 '\' 开头，则去除 '\'，并取第一部分（应用名称）作为前缀
     * @param $namespace
     * @return mixed
     */
    public static function convertNamespaceToTablePrefix($namespace)
    {
        if (!preg_match('/^\S/', $namespace)) $namespace = substr($namespace, 1);
        $list = explode('\\', $namespace);

        return $list[0];
    }
}