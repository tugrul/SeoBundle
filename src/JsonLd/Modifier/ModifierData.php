<?php

namespace Tug\SeoBundle\JsonLd\Modifier;

use Tug\SeoBundle\JsonLd\Attribute\Type as JsonLdType;

class ModifierData
{
    public readonly JsonLdType $type;

    public readonly int $level;

    public readonly array $value;

    public readonly ?object $object;

    public function __construct(JsonLdType $type, int $level, array $value, ?object $object = null)
    {
        $this->type = $type;

        $this->level = $level;

        $this->value = $value;

        $this->object = $object;
    }
}
