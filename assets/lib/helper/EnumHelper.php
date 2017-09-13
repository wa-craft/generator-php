<?php
namespace app\common\helper;

/**
 * 保存并提供处理所有枚举值的助手类
 * Class Enum
 * @package app\common\helper
 */
class EnumHelper
{
    /**
     * 平台类型枚举值
     * @var array
     */
    protected static $platform_types = [
        ['key' => 0, 'title' => '普通用户'],
        ['key' => 1, 'title' => '平台'],
        ['key' => 2, 'title' => '企业']
    ];

    /**
     * 通过属性名称获取枚举值数组
     * @param string $enum_type
     * @return array
     */
    public static function getEnumsByName(string $enum_type)
    {
        return self::$$enum_type ?? [];
    }

    /**
     * 通过属性名称和索引关键字获取枚举值
     * @param string $enum_type
     * @param $key
     * @return array
     */
    public static function getElementByKey(string $enum_type, $key)
    {
        $enums = self::$$enum_type;
        return (!empty($enums))
            ? (function () use ($enums, $key) {
                foreach ($enums as $item) {
                    if ($item['key'] === $key) return $item;
                }
                return [];
            })()
            : [];
    }
}