<?php
/**
 * 自动装载程序
 */

$src_path = SRC_PATH.'/lib';
$files = scandir($src_path);
foreach ($files as $file) {
    $src_file_name = $src_path . '/' . $file;
    if (is_file($src_file_name)) {
        if(preg_match('/\.php$/', $src_file_name)) {
            require $src_file_name;
        }
    }
}