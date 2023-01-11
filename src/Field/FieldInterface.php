<?php

namespace Tug\SeoBundle\Field;

interface FieldInterface
{
    /**
     * @return string[]
     */
    public function getNamespace(): array;

    public function buildModels(FieldData $fieldData): iterable;
}
