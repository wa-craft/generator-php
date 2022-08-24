<?php

declare(strict_types=1);

namespace generator\http;

class Request
{
    /**
     * @var RequestMethodType 请求方法类型
     */
    public RequestMethodType $methodType = RequestMethodType::POST;
    /**
     * @var array 请求头部信息数组
     */
    public array $header = [];
    /**
     * @var array 请求参数列表
     */
    public array $queries = [];
    /**
     * @var RequestBody|null 请求主体
     */
    public ?RequestBody $body = null;
}
