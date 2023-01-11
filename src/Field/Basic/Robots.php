<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Meta;

class Robots implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['robots'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        $meta = new Meta();
        $meta->setName('robots');
        $meta->setContent($content);

        yield $meta;
    }
}
