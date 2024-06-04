<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\JsonLd\Filter\FilterData;

interface JsonLdInterface
{
    public function setDefaultContext(?string $context);

    public function setTypes(array $types);

    public function getFieldTypes(string|array $type, string $field): array;

    public function setFilter(string $handle, callable $filter);

    public function applyFilter(string $handle, FilterData $data): mixed;
}
