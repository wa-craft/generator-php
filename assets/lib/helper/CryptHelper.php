<?php
namespace app\common\helper;

/**
 * Class Crypt
 * @package app\common\helper
 */
final class CryptHelper
{

    public static function encrypt($str, $key)
    {
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        return mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    }
    
    public static function decrypt($str, $key)
    {
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
//      $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }

    public static function md5_salt($pwd, $salt = null)
    {
        if (is_null($salt)) {
            $salt = config('app_key');
        }
        $pwd = md5(md5($pwd) . $salt);
        return $pwd;
    }
        
}