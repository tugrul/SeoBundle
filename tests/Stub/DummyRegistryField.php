<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};

class DummyRegistryField implements FieldInterface
{
    protected array $namespace;

    public function __construct(array $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return $this->namespace;
    }

    public function buildModels(FieldData $fieldData): iterable
    {
    }
}
