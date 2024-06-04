<?php

namespace Tug\SeoBundle\JsonLd\Filter;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

class FilterData
{
    public readonly string $filterName;

    public readonly JsonLd\Type $type;

    public readonly JsonLd\Property $property;

    public readonly int $level;

    public readonly mixed $value;

    public readonly array $params;

    public function __construct(string $filterName, JsonLd\Type $type, JsonLd\Property $property,
                                int $level, mixed $value, array $params)
    {
        $this->filterName = $filterName;

        $this->type = $type;

        $this->property = $property;

        $this->level = $level;

        $this->value = $value;

        $this->params = $params;
    }
}
