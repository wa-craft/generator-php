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
            if ($obj instanceof Processor) {
                $_name = strtolower($processor_type->name);
                $src = ROOT_PATH . '/resource/' . $_name . '/' . ($cache->get('project')[$_name] ?: '') . '/src';
                $tar = ($cache->get('target_paths')[$_name]) ? ROOT_PATH . '/' . $cache->get('target_paths')[$_name] : ROOT_PATH . '/deploy/' . $_name;

                //拷贝基本文件
                FileHelper::copyFiles($src, $tar);
                return $obj;
            } else {
                return null;
            }
        }
    }
}
