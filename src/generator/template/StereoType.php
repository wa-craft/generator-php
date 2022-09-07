<?php

declare(strict_types=1);

namespace generator\template;

use generator\parser\openapi\Schema;

/**
 * 模板文件数据模式抽象类
 */
abstract class StereoType
{
    public string $name = '';
    public string $lang = '';
}
