<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\{FieldData, FieldInterface, TranslatableFieldInterface, TranslatableFieldTrait};
use Tug\SeoBundle\Model\Title as TitleModel;

class Title implements FieldInterface, TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return ['title'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $parts = [];

        $options = $fieldData->getOptions();

        if (!empty($options['single'])) {
            $title = new TitleModel();
            $title->setValue($this->translator->translate($fieldData->getContent(), $fieldData->getParameters()));
            yield $title;

            return;
        }

        do {
            $parts[] = $this->translator->translate($fieldData->getContent(), $fieldData->getParameters());
            $fieldData = $fieldData->getParent();
        } while (!is_null($fieldData));

        if (!empty($options['reverse'])) {
            $parts = array_reverse($parts);
        }

        $title = new TitleModel();
        $title->setValue(implode(sprintf(' %s ', $options['separator'] ?? '-'), $parts));

        yield $title;
    }
}
