<?php

namespace Tug\SeoBundle\JsonLd\Reflection;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

class Field
{
    public readonly mixed $value;

    public readonly ?JsonLd\Property $property;

    public function __construct(mixed $value, ?JsonLd\Property $property = null)
    {
        $this->value = $value;

        $this->property = $property;
    }

    public function getFilters(): array
    {
        $result = [];

        if (!is_null($this->property)) {
            foreach ($this->property->filters as $key => $value) {
                if (is_string($key)) {
                    $result[$key] = is_array($value) ? $value : [];
                } elseif (is_string($value)) {
                    $result[$value] = [];
                }
            }
        }

        return $result;
    }

    public function getTypes(): array
    {
        return is_null($this->property) ? [] : $this->property->types;
    }
}
