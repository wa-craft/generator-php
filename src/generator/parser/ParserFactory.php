<?php

declare(strict_types=1);

namespace generator\parser;

use generator\helper\ClassHelper;

final class ParserFactory
{
    public static function create(ParserType $parser_type): Parser | null
    {
        if ($parser_type instanceof ParserType) {
            $obj = ClassHelper::create("generator\\parser\\" . $parser_type->name);

            return ($obj instanceof Parser) ? $obj : null;
        }
    }

    public static function createByName(string $parser_name): Parser | null
    {
        $pt = null;
        foreach(ParserType::cases() as $case) {
            if(strtolower($case->name) == strtolower($parser_name)) {
                $pt = $case;
                break;
            }
        }

        return ($pt !== null) ? self::create($pt) : null;
    }
}
