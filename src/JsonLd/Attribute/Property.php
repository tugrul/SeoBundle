<?php

namespace Tug\SeoBundle\JsonLd\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Property
{
    public readonly string $name;

    public readonly array $types;

    public readonly array $owners;

    public readonly int|array|null $level;

    public readonly array $filters;

    public function __construct(string $name, string|array $types = [], string|array $owners = [],
                                int|array|null $level = null, string|array $filters = [])
    {
        $this->name = $name;

        $this->types = is_string($types) ? [$types] : $types;

        $this->owners = is_string($owners) ? [$owners] : $owners;

        $this->level = $level;

        $this->filters = is_string($filters) ? [$filters] : $filters;
    }
}