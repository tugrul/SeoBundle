<?php

namespace Tug\SeoBundle\Attribute\JsonLd;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Type
{
    public readonly string $name;

    public readonly ?string $context;

    public function __construct(string $name, ?string $context = null)
    {
        $this->name = $name;

        $this->context = $context;
    }

    public function toArray(): array
    {
        if (!empty($context)) {
            return [
                '@context' => $this->context,
                '@type' => $this->name
            ];
        }

        return ['@type' => $this->name];
    }
}