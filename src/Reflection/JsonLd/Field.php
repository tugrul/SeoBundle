<?php

namespace Tug\SeoBundle\Reflection\JsonLd;

class Field
{
    public readonly mixed $value;

    public readonly array $types;

    public function __construct(mixed $value, array $types)
    {
        $this->value = $value;

        $this->types = $types;
    }
}
