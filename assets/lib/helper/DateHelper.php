<?php
namespace app\common\helper;

/**
 * Class Date 跟日期相关的工具类
 * @package app\common\helper
 */
final class DateHelper
{
    /**
     * 将中文时间字符串中的中文文字替代成连接符
     * @param $date_str
     * @param $connector
     * @return mixed
     */
    public static function replaceZhCharacters($date_str, $connector)
    {
        $zh_characters = ['年', '月', '日', '点', '分', '秒'];

        foreach ($zh_characters as $char) {
            $date_str = str_replace($char, $connector, $date_str);
        }

        return $date_str;
    }

    /**
     * 人性化显示发生在过去的时间点
     * @param $date_time
     * @return string
     */
    public static function humanized($date_time)
    {
        $date_time1 = new \DateTime($date_time);
        $date_time2 = new \DateTime("now");
        $interval = $date_time2->diff($date_time1);

        $years = $interval->format("%y");
        $months = $interval->format("%m");
        $days = $interval->format("%d");
        $hours = $interval->format("%h");
        $minutes = $interval->format("%i");
        $seconds = $interval->format("%s");

        if ($years > 0) return $years . '年前';
        if ($months > 0) return $months . '个月前';
        if ($days > 0) return $days . '天前';
        if ($hours > 0) return $hours . '个小时前';
        if ($minutes > 0) return $minutes . '分钟前';
        if ($seconds > 0) return $seconds . '秒前';
        return '';
    }

    /**
     * 校验日期格式是否合法
     * @param $date
     * @return bool
     */
    public static function checkDateFormat($date)
    {
        $date = DateHelper::replaceZhCharacters($date, '-');
        return preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\-?$/', $date);
    }

    /**
     * 获取当前时间，并返回下一个整十分钟的时间字符串
     * @return false|string
     */
    public static function floorTenMinute()
    {
        $t = (int)date('i');
        $gap = 10-($t - floor($t/10)*10);

        $date = date('Y-m-d H:i:00', strtotime("+$gap minutes"));

        return $date;
    }

    /**
     * 通过参数生成跟日期有关的随机码数值字符串
     * @param string $param
     * @return string
     */
    public static function generateSerial($param = '')
    {
        $p1 = (int)date('Ymd') - 20121221;
        $p2 = (int)$param + (int)date('i') + 10000;
        $suffix = NumberHelper::generateRandomUniqueCode(2);
        return (string)$p1 . (string)$p2 . $suffix;
    }
}