<?php

declare(strict_types=1);

namespace generator\processor;

use generator\helper\{ClassHelper, FileHelper};
use generator\Cache;

final class ProcessorFactory
{
    public static function create(ProcessorType $processor_type): Processor | null
    {
        if ($processor_type instanceof ProcessorType) {
            $cache = Cache::getInstance();
            $type_name = strtolower($processor_type->name);
            $obj = ClassHelper::create("generator\\processor\\" . ucfirst($type_name));

            //创建路径
            $_prj_src_path = '';
            $_prj_tar_path = '';
            if ($obj instanceof Processor) {
                $_name = strtolower($processor_type->name);

                //判断并设置原始路径
                $project = $cache->get('project') ?: [];
                if (!empty($project)) {
                    $_prj_src_path = (array_key_exists($_name, $project)) ? $project[$_name] : '';
                }

                $target_paths = $cache->get('target_paths');
                if (!empty($target_paths)) {
                    $_prj_tar_path = (array_key_exists($_name, $target_paths)) ? $target_paths[$_name] : '';
                }

                if (empty($_prj_src_path) || empty($_prj_tar_path) || empty($cache->get('project')[$type_name])) {
                    echo "WARNING: CANNOT find the source path or the target path of resouce \"{$_name}\"!" . PHP_EOL;
                } else {
                    $src = ROOT_PATH . '/resource/' . $_name . '/' . $_prj_src_path . '/src';
                    $tar = ROOT_PATH . '/' . $cache->get('target_paths')[$_name];

                    //拷贝基本文件
                    FileHelper::copyFiles($src, $tar);
                }

                return $obj;
            } else {
                return null;
            }
        }
    }
}
