<?php

namespace Tug\SeoBundle\JsonLd\Attribute;

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
        if (!empty($this->context)) {
            return [
                '@context' => $this->context,
                '@type' => $this->name
            ];
        }

        return ['@type' => $this->name];
    }

    public static function from(string|array $type): static
    {
        if (is_string($type)) {
            return new static($type);
        }

        $count = count($type);

        switch ($count) {
            case 0: throw new \InvalidArgumentException('Empty array is not suitable to solve JSON-LD type.');
            case 1: return new static($type[0]);
            case 2: return new static($type[1], $type[0]);
        }

        $message = 'Array should have only @type and/nor @context values.' . sprintf('It have %d items', $count);
        throw new \InvalidArgumentException($message);
    }
}
