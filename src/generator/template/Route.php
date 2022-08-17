<?php

namespace generator\template;

use generator\helper\FileHelper;

class Route extends Template
{
    protected string $controller = '';
    protected string $action = '';
    protected string $path = '';

    public function __construct(array $params)
    {
        if (key_exists('controller', $params)) {
            $this->controller = $params['controller'];
        }
        if (key_exists('action', $params)) {
            $this->controller = $params['action'];
        }
        if (key_exists('path', $params)) {
            $this->path = $params['path'];
        }

        if (key_exists('path', $params)) {
            $this->path = $params['path'];
        }

        $this->stub = file_get_contents(ROOT_PATH . '/template/php/route.tmpl');
    }

    public function getData(): array
    {
        return [
          'routes' => [
              'method' => 'post',
              'name' => $this->path,
              'controller' => $this->controller
          ]
        ];
    }

    public function writeToFile()
    {
        $me = new \Mustache_Engine();
        $s = $me->render($this->stub, $this->getData());
        if (!empty($this->path)) {
            $paths = explode('/', $this->path);
            array_pop($paths);

            $path = implode('/', $paths);
            FileHelper::mkdir($path, false);
            file_put_contents($this->path, $s);
        }
    }
}
