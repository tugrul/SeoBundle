<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface, TranslatableFieldInterface, TranslatableFieldTrait};
use Tug\SeoBundle\Model\Meta;

class Description implements FieldInterface, TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return ['description'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();
        $parameters = $fieldData->getParameters();

        $model = new Meta();
        $model->setName('description');
        $model->setContent($this->translator->translate($content, $parameters));

        yield $model;
    }
}
