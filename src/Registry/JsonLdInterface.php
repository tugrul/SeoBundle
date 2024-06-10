<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\JsonLd\Filter\FilterData;
use Tug\SeoBundle\JsonLd\Modifier\{ModifierData, ModifierInterface};
use Tug\SeoBundle\JsonLd\Attribute\Type as JsonLdType;

interface JsonLdInterface
{
    public function setDefaultContext(?string $context);

    public function setTypes(array $types);

    public function getFieldTypes(JsonLdType $type, string $field): array;

    public function setFilter(string $handle, callable $filter);

    public function applyFilter(string $handle, FilterData $data): mixed;

    public function setModifier(ModifierInterface $modifier);

    public function applyModifier(ModifierData $data): iterable;
}
