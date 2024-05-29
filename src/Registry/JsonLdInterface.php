<?php

namespace Tug\SeoBundle\Registry;

interface JsonLdInterface
{
    public function setDefaultContext(?string $context);

    public function setTypes(array $types);

    public function getFieldTypes(string|array $type, string $field): array;
}
