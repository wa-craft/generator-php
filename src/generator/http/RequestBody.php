<?php

declare(strict_types=1);

namespace generator\http;

/**
 * 请求主体
 */
class RequestBody
{
    /**
     * @var RequestBodyType 请求主体类型
     */
    public RequestBodyType $bodyType = RequestBodyType::JSON;
    /**
     * @var string 请求主体内容
     */
    public string $content = '';
}
