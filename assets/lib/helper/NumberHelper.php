<?php
namespace app\common\helper;

/**
 * Class Number 跟数字相关的
 * @package app\common\helper
 */
final class NumberHelper
{
    const MODE_NUMBER = 1;
    const MODE_ALPHA = 2;
    const MODE_ALPHA_MULTI = 3;

    /**
     * 生成随机码
     * @param int $code_length 随机码长度
     * @param int $mode 密码本类型，1-数字，2-数字字母
     * @return string 随机码
     */
    public static function generateRandomUniqueCode(int $code_length = 6, $mode = 1)
    {
        $code = '';

        switch ($mode) {
            case self::MODE_NUMBER:
                $code_book = '0123456789';
                break;
            case self::MODE_ALPHA:
                $code_book = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
                break;
            case self::MODE_ALPHA_MULTI:
                $code_book = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz';
                break;
            default:
                $code_book = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        }

        for ($i = 0; $i < $code_length; $i++) {
            $code .= $code_book[rand(0, strlen($code_book) - 1)];
        }
        return $code;
    }

    /**
     * 将金额变为可读取的值
     * @param $money
     * @return string
     */
    public static function makeMoneyString($money)
    {
        $numbers = explode('.', $money);
        $s = '';

        if (isset($numbers[1])) {
            $s = $numbers[0] . '.' . $numbers[1];
            if (strlen($numbers[1]) == 1) $s .= '0';
        } else {
            $s = $numbers[0] . '.00';
        }
        return $s;
    }

    /**
     * 通过给出的数字上标与下标生成包含区间数值的数组
     * @param int $first
     * @param int $last
     * @param int $step
     * @return array
     */
    public static function makeNumberArray(int $first = 0, int $last, int $step = 1)
    {
        $a = [];
        if ($first <= $last) {
            for ($i = $first; $i <= $last; $i += $step) {
                $a[] = $i;
            }
        } else {
            for ($i = $first; $i >= $last; $i -= $step) {
                $a[] = $i;
            }
        }

        return $a;
    }

    /**
     * 获取数组中某个下标的值，通常用于在数据集合中根据某个条件查找数据结果
     * @param array $array 数据集合
     * @param string $key 下标关键字
     * @param string $value 查询的值
     * @return string
     */
    public static function getArrayElement($array, $key, $value)
    {
        $result = '';
        foreach ($array as $v) {
            if ($v[$key] == $value) {
                $result = $v;
                break;
            }
        }
        return $result;
    }
}