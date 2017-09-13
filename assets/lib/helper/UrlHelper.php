<?php
namespace app\common\helper;

/**
 * Class Url 跟网址相关的工具类
 * @package app\common\helper
 */
final class UrlHelper
{
    /**
     * 访问 url 地址，并获取返回结果
     * @param string $url
     * @return bool|string
     */
    public static function fetchUrl($url = '')
    {
        $file = "";
        if ($url == '') return false;
        $fp = fopen($url, 'r') or exit('Open url faild!');
        if ($fp) {
            while (!feof($fp)) {
                $file .= fgets($fp) . "";
            }
            fclose($fp);
        }
        return $file;
    }

    public static function isGET()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    public static function isPOST()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public static function getClientIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset ($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return $ip;
    }

    public static function getRefererUrl()
    {
        $referer = request()->header('Referer');
        return $referer;
    }
    
    /**
     * 域名是否以指定开头
     * @param $hostprefix
     * @return bool
     */
    public static function isDomainStartWith($hostprefixs)
    {
        $domain = request()->domain();
        preg_match("/^(http:\/\/)?([^\/]+)/i", $domain, $matches);
        if (count($matches) > 2) {
            $host = $matches[2];
            if (is_array($hostprefixs)) {
                $ispass = false;
                foreach ($hostprefixs as $hostprefix) {
                    if (strpos($host, $hostprefix) === 0) {
                        $ispass = true;
                        break;
                    }
                }
                return $ispass;
            } else {
                if (strpos($host, $hostprefixs) === 0) {
                    return true;
                }
            }

        }
        return false;
    }

}