<?php

declare(strict_types=1);

namespace generator\parser\openapi;

class Schema
{
    private string $name = '';
    private string $type = 'object';
    private array $properties = [];
    private string $package = '';

    public function __construct($schema)
    {
        $this->name = ucfirst($schema['name']) ?: '';
        $this->type = $schema['type'] ?: 'object';

        //创建属性列表
        $properties = array_key_exists('properties', $schema) ? $schema['properties'] : [];
        foreach ($properties as $prop_key => $property) {
            $property['name'] = $prop_key;
            $this->properties[] = new Property($property);
        }

        //设置路径
        $path = array_key_exists('x-api-folder', $schema) ? $schema['x-api-folder'] : '';
        $this->package = $path;
    }
}
