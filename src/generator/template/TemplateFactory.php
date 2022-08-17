<?php

namespace generator\template;

use generator\helper\ClassHelper;

class TemplateFactory
{
    public static function create(TemplateType $type, array $params = []): Template|null
    {
        $obj = ClassHelper::create("generator\\template\\" . $type->name, $params);
        return ($obj instanceof Template) ? $obj : null;
    }
}
