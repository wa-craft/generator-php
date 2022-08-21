<?php

declare(strict_types=1);

namespace generator\template;

use generator\helper\ClassHelper;

final class StereotypeFactory
{
    public static function create(array $params = []): StereoType|null
    {
        $obj = ClassHelper::create("generator\\template\\" . $type->name, $params);
        return ($obj instanceof StereoType) ? $obj : null;
    }
}
