<?php

namespace app\common\helper;

/**
 * Class Tree 树状结构工具类
 * @package app\common\helper
 */
class TreeHelper
{
    /**
     * 根据给出的对象列表生成树状多级数组
     * @param $list
     * @return array
     */
    public static function makeTree($list)
    {
        //TODO 以后修改为 yield
        $tree = [];
        foreach ($list as $item) {
            if ($item . pid == 0) $tree[] = $item;
        }

        foreach ($tree as $node) {
            $node['children'] = [];
            foreach ($list as $item) {
                if ($item . pid == $node . id) $node['children'][] = $item;
            }
        }

        return $tree;
    }

    /**
     * 将数据表根据树装结构重新排序，并使用级别符号进行区分
     * @param $list
     * @param string $opr 分隔符
     * @return array
     */
    public static function makeFlatTree($list, $opr = '>')
    {
        $tree = [];
        if (empty($list)) return $tree;
        foreach (self::ergodicList($list, 0, $opr) as $item) {
            $tree[] = $item;
        }
        return $tree;
    }

    /**
     * 迭代遍历数据对象
     * @param $list
     * @param int $pid
     * @param string $opr 分隔符
     * @return \Generator
     */
    protected static function ergodicList($list, $pid = 0, $opr)
    {
        foreach ($list as &$item) {
            if ($item['pid'] === $pid) {
                $item['name'] = str_repeat($opr, $item['level']) . $item['name'];
                yield $item;
                yield from self::ergodicList($list, $item['id'], $opr);
            }
        }
    }
}