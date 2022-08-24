<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\ClassHelper;

final class ParserFactory
{
    /**
     * 通过传递的 parse 类型来创建分析器
     * @param ParserType $parser_type
     * @return Parser|null
     */
    public static function create(ParserType $parser_type): Parser|null
    {
        $obj = ClassHelper::create("generator\\parser\\" . $parser_type->name);
        return ($obj instanceof Parser) ? $obj : null;
    }

    /**
     * 通过传递的 parser 名称来创建分析器
     * @param string $parser_name
     * @return Parser|null
     */
    public static function createByName(string $parser_name): Parser|null
    {
        $pt = null;
        foreach (ParserType::cases() as $case) {
            if (strtolower($case->name) == strtolower($parser_name)) {
                $pt = $case;
                break;
            }
        }

        return ($pt !== null) ? self::create($pt) : null;
    }
}
