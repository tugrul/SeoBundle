<?php

namespace Tug\SeoBundle\Reflection\JsonLd;

use Tug\SeoBundle\Attribute\JsonLd;

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
