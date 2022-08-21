<?php

namespace generator\template;

use generator\helper\ClassHelper;

class TemplateFactory
{
    public static function create(string $stereoType, array $params = []): StereoType|null
    {
        $obj = ClassHelper::create("generator\\template\\" . $stereoType, $params);
        //处理 schema 对象
        if (!empty($params)) {
        }
        return ($obj instanceof StereoType) ? $obj : null;
    }
}
