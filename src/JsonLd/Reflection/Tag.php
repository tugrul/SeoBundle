<?php

namespace Tug\SeoBundle\JsonLd\Reflection;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

class Tag
{
    public readonly \ReflectionProperty | \ReflectionMethod $field;

    public readonly JsonLd\Property $property;

    public function __construct(\ReflectionProperty | \ReflectionMethod $field, JsonLd\Property $property)
    {
        $this->field = $field;

        $this->property = $property;
    }
}
