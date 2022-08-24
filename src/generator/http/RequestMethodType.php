<?php

declare(strict_types=1);

namespace generator\http;

/**
 * 请求类型
 */
enum RequestMethodType
{
    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;
    case OPTIONS;
    case HEAD;
    case TRACE;
}
