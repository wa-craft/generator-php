<?php

declare(strict_types=1);

namespace generator\parser;

enum ParserType
{
    case Apifox;
    case Legacy;
    case Openapi;
}
