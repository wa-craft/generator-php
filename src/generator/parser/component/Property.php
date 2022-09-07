<?php

declare(strict_types=1);

namespace generator\parser\component;

class Property
{
    public string $name = '';
    public string $title = '';
    public string $type = 'string';
    public string $value = '';

    public function __construct($property)
    {
        $this->name = array_key_exists('name', $property) ? $property['name'] : '';
        $this->type = array_key_exists('type', $property) ? $property['type'] : '';

        switch ($this->type) {
            case 'integer':
                $this->type = 'int';
                $this->value = "0";
                break;
            case 'boolean':
                $this->type = 'bool';
                $this->value = 'false';
                break;
            case 'string':
                $this->value = "''";
                break;
            case 'object':
                $this->type = 'mixed';
                $this->value = 'null';
                break;
        }

        if (array_key_exists('$ref', $property)) {
            $this->name = strtolower($this->name) . '_id';
            $this->type = 'int';
            $this->value = '0';
        }
        $this->title = array_key_exists('title', $property) ? $property['title'] : '';
    }
}
