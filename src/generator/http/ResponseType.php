<?php

declare(strict_types=1);

namespace generator\http;

enum ResponseType
{
    case JSON;
    case XML;
    case HTML;
    case RAW;
    case BINARY;
}
