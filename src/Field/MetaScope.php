<?php

namespace Tug\SeoBundle\Field;

use Tug\SeoBundle\Model\Meta;

abstract class MetaScope implements FieldInterface
{
    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return [$this->getRootName(), $this->getName()];
    }

    public function getPrefix(string $separator = ':'): string
    {
        return implode($separator, $this->getNamespace());
    }

    public function getTag(string $name = ''): Meta
    {
        $meta = new Meta();

        $this->setMetaHandle($meta, $this->getPrefix() . (!empty($name) ? ':' . $name : ''));

        return $meta;
    }

    protected function setMetaHandle(Meta $meta, string $handle): void
    {
        $meta->setProperty($handle);
    }

    abstract protected function getRootName(): string;

    abstract protected function getName(): string;
}
