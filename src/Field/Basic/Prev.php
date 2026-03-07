<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Link;

class Prev implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['prev'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $link = new Link();
        $link->setRel('prev');
        $link->setHref($fieldData->getContent());

        yield $link;
    }
}
