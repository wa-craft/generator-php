<?php

namespace generator\resource;

use generator\Cache;
use generator\helper\ClassHelper;

class ResourceFactory
{
    public static function create(ResourceType $resource_type, array $params = []): Resource|null
    {
        $cache = Cache::getInstance();
        $type_name = strtolower($resource_type->name);
        $obj = ClassHelper::create("generator\\resource\\" . ucfirst($type_name));

        //创建路径
        $_prj_src_path = '';
        $_prj_tar_path = '';
        if ($obj instanceof Resource) {
            $_name = strtolower($resource_type->name);

            //判断并设置原始路径
            $project = $cache->get('project') ?: [];
            if (!empty($project)) {
                $_prj_src_path = (array_key_exists($_name, $project)) ? $project[$_name] : '';
            }

            $target_paths = $cache->get('target_paths');
            if (!empty($target_paths)) {
                $_prj_tar_path = (array_key_exists($_name, $target_paths)) ? $target_paths[$_name] : '';
            }

            if (empty($_prj_src_path) || empty($_prj_tar_path) || empty($cache->get('project')[$_name])) {
                echo "WARNING: CANNOT find the source path or the target path of resource \"{$_name}\"!" . PHP_EOL;
            } else {
                $src = ROOT_PATH . '/resource/' . $_name . '/' . $_prj_src_path;
                $tar = ROOT_PATH . '/' . $cache->get('target_paths')[$_name];
                //配置处理器的资源管理器
                $obj->setSourcePath($src);
                $obj->setTargetPath($tar);
            }

            return $obj;
        } else {
            return null;
        }
    }
}
