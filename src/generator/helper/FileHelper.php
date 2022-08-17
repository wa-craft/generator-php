<?php

namespace generator\helper;

/**
 * Class File 与文件相关的助手类
 */
class FileHelper
{
    /**
     * 判断目录是否存在，如果不存在则创建，可递归创建所有的父目录
     * @param string $path
     * @param bool $force
     */
    public static function mkdir(string $path, bool $force): void
    {
        if ($force) {
            exec('rm -rf ' . $path);
        }
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        echo "INFO: creating {$path} ..." . PHP_EOL;
    }

    /**
     * 创建一组路径
     * @param array $paths
     * @param bool $force
     */
    public static function mkdirs(array $paths = [], bool $force = true): void
    {
        if (empty($paths)) {
            echo "ERROR: There is no target paths configured!";
        }

        foreach ($paths as $path) {
            FileHelper::mkdir($path, $force);
        }
    }

    /**
     * 扫描某个路径下的所有文件，并拷贝到目标路径下，支持递归
     * @param $src_path
     * @param $tar_path
     */
    public static function copyFiles($src_path, $tar_path): void
    {
        FileHelper::mkdir($tar_path, true);

        $files = scandir($src_path);
        foreach ($files as $file) {
            $src_file_name = $src_path . '/' . $file;
            $tar_file_name = $tar_path . '/' . $file;
            if (is_file($src_file_name)) {
                copy($src_file_name, $tar_file_name);
            }

            if (is_dir($src_file_name) && !preg_match('/\.+$/', $src_file_name)) {
                FileHelper::copyFiles($src_file_name, $tar_file_name);
            }
        }
    }

    /**
     * 从不同格式的文件中读取内容，并转换为PHP数组
     */
    public static function readDataFromFile($file): mixed
    {
        $data = [];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        switch (strtolower($ext)) {
            case 'yml':
            case 'yaml':
                $data = yaml_parse_file($file);
                break;
            case 'json':
                $json_content = file_get_contents($file);
                if (!empty($json_content)) {
                    $data = json_decode($json_content, true);
                }
                break;
            case 'php':
            default:
                $data = require $file;
        }

        return $data;
    }
}
