<?php

declare(strict_types=1);

namespace generator\http;

/**
 * http 返回值
 */
class Response
{
    /**
     * @var int 状态码
     */
    public int $code = 200;
    /**
     * @var string 返回名称
     */
    public string $name = 'success';
    /**
     * @var ResponseType 返回类型
     */
    public ResponseType $responseType = ResponseType::JSON;
}
