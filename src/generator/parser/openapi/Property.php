<?php

declare(strict_types=1);

namespace generator\parser\openapi;

class Property
{
    private string $name = '';
    private string $title = '';
    private string $type = 'string';
    public function __construct($property)
    {
        $this->name = array_key_exists('name', $property) ? $property['name'] : '';
        $this->type = array_key_exists('type', $property) ? $property['type'] : '';
        $this->title = array_key_exists('title', $property) ? $property['title'] : '';
    }
}
