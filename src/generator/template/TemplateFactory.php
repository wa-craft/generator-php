<?php

namespace generator\template;

use generator\helper\ClassHelper;

class TemplateFactory
{
    public static function create(string $stereoType, array $params = []): StereoType|null
    {
        list($lang, $type) = explode('/', $stereoType);
        $obj = ClassHelper::create("generator\\template\\"
            . strtolower($lang)
            . "\\Stereotype"
            . ucfirst($type), $params);
        //处理 schema 对象
        if (!empty($params)) {
        }
        return ($obj instanceof StereoType) ? $obj : null;
    }
}
