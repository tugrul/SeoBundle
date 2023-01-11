<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Link;

class AmpHtml implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['amphtml'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        $link = new Link();
        $link->setRel('amphtml');
        $link->setHref($content);

        yield $link;
    }
}
