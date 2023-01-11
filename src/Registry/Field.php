<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Field\FieldInterface;
use Tug\SeoBundle\Registry\FieldInterface as ParameterRegistryInterface;

class Field implements ParameterRegistryInterface
{
    protected array $fields = [];

    public function set(FieldInterface $parameter): self
    {
        $namespace = $parameter->getNamespace();
        $handleName = implode(':', $namespace);

        $this->fields[$handleName] = $parameter;

        return $this;
    }

    public function get(array $namespace): FieldInterface
    {
        $handleName = implode(':', $namespace);

        if (!isset($this->fields[$handleName])) {
            throw new \RuntimeException('There is no field defined for namespace ' . $handleName);
        }

        return $this->fields[$handleName];
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->fields;
    }
}
