<?php
/**
 * 自动装载程序
 */
//TODO 使用 generator 遍历目录下的所有 php 文件，并包含
$src_path = SRC_PATH . '/lib';
$files = scandir($src_path);
foreach ($files as $file) {
    $src_file_name = $src_path . '/' . $file;
    if (is_file($src_file_name)) {
        if (preg_match('/\.php$/', $src_file_name)) {
            require $src_file_name;
        }
    }
}