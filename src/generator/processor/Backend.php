<?php

declare(strict_types=1);

namespace generator\processor;

use generator\helper\FileHelper;

class Backend extends Processor
{
    public function process(): void
    {
        //克隆基本目录结构和代码
        if ($this->res !== null and $this->res instanceof Resource) {
            $this->res->clone();
        }

        $data = [
            "functions" => [
                [
                    "name" => "foo",
                    "has_return_type" => false,
                    "code" => "return 'foo';"
                ],
                [
                    "name" => "bar",
                    "has_return_type" => true,
                    "return_type" => "string",
                    "code" => "return 'bar';"
                ]
            ]
        ];

        /*
        $me = new \Mustache_Engine();
        $template = file_get_contents(ROOT_PATH . '/template/php/function.tmpl');
        $s = $me->render($template, $data);
        var_dump($s);
        */
    }
}
