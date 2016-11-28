<?php
/**
 * 自动装载程序
 */
$src_path = SRC_PATH . '/thinkbuilder';
foreach (scan($src_path) as $file) {
    $src_file_name = $src_path . '/' . $file;
    if (is_file($file)) {
        if (preg_match('/\.php$/', $file)) {
            require $file;
        }
    }
}

/**
 * 迭代遍历并返回指定目录下的所有文件（非目录）
 * @param string $dir 指定的目录
 * @return array Generator 生成器生成的结果
 */
function scan($dir)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if (is_dir("$dir/$file")) {
            if (preg_match('/^\./', $file) === 0) yield from scan("$dir/$file");
        } else {
            yield "$dir/$file";
        }
    }
}
