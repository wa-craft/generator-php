<?php
/**
 * 自动装载程序
 */

/**
 * 类的自动注册方法
 */
spl_autoload_register(function ($class) {
    include classNameToPath($class);
});

//自动装载文件中的类
$src_path = SRC_PATH . '/thinkbuilder';

foreach (scan($src_path) as $class => $file) {
    if (is_file($file)) {
        if (preg_match('/\.php$/', $file)) {
            spl_autoload($class);
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
            yield pathToClassName("$dir/$file") => "$dir/$file";
        }
    }
}

/**
 * 把类文件的路径转换为类名
 * @param $path
 * @return mixed
 */
function pathToClassName($path)
{
    $class = str_replace('.php', '', $path);
    $class = str_replace(SRC_PATH . '/', '', $class);
    $class = str_replace('/', '\\', $class);
    return $class;
}

/**
 * 把类名转换为文件名
 * @param $class
 * @return mixed|string
 */
function classNameToPath($class)
{
    $path = str_replace('\\', '/', $class);
    $path = SRC_PATH . '/' . $path . '.php';
    return $path;
}
