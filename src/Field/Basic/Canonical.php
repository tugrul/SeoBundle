<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Link;

class Canonical implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['canonical'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $link = new Link();
        $link->setRel('canonical');
        $link->setHref($fieldData->getContent());

        yield $link;
    }
}
