<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Link;

class Next implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['next'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $link = new Link();
        $link->setRel('next');
        $link->setHref($fieldData->getContent());

        yield $link;
    }
}
