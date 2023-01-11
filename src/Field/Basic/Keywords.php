<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface, TranslatableFieldInterface, TranslatableFieldTrait};
use Tug\SeoBundle\Model\Meta;

class Keywords implements FieldInterface, TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    public function getNamespace(): array
    {
        return ['keywords'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();
        $parameters = $fieldData->getParameters();

        $meta = new Meta();
        $meta->setName('keywords');
        $meta->setContent($this->translator->translate($content, $parameters));

        yield $meta;
    }
}
