<?php

declare(strict_types=1);

namespace generator\parser\component;

class Schema
{
    public string $name = '';
    public string $type = 'object';
    public array $properties = [];
    public array $functions = [];
    public string $namespace = '';
    public array $template_rule = [];

    public function __construct($schema)
    {
        $this->name = ucfirst($schema['name']) ?: '';
        $this->type = $schema['type'] ?: 'object';
        $this->template_rule = $schema['template_rule'] ?? [];

        //创建属性列表
        $properties = array_key_exists('properties', $schema) ? $schema['properties'] : [];
        foreach ($properties as $prop_key => $property) {
            $property['name'] = $prop_key;
            echo $prop_key;
            $this->properties[] = new Property($property);

            //如果存在对其他对象的引用，则自动创建对应的属性和方法
            if (array_key_exists('$ref', $property)) {
                $this->functions[] = [
                    'scope' => 'public',
                    'name' => 'get' . ucfirst(str_replace('#/components/schemas/', '', $property['$ref'])) . 'Attr'
                ];
            }
        }

        //设置路径
        $path = array_key_exists('x-apifox-folder', $schema) ? $schema['x-apifox-folder'] : '';
        $this->namespace = $path;
    }
}
