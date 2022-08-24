<?php

declare(strict_types=1);

namespace generator\http;

enum RequestBodyType
{
    case NONE;
    case FORM_DATA;
    case X_WWW_FORM_URLENCODED;
    case JSON;
    case XML;
    case RAW;
    case BINARY;
}
