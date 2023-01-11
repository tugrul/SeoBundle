<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\{FieldData, TranslatableFieldInterface, TranslatableFieldTrait};

class Title extends Base implements TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    function getName(): string
    {
        return 'title';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $parts = [];

        $options = $fieldData->getOptions();

        if (!empty($options['single'])) {

            yield $this->getTag()->setContent($this->translator->translate($fieldData->getContent(),
                $fieldData->getParameters()));
            return;
        }

        do {
            $parts[] = $this->translator->translate($fieldData->getContent(), $fieldData->getParameters());
            $fieldData = $fieldData->getParent();
        } while (!is_null($fieldData));

        if (!empty($options['reverse'])) {
            $parts = array_reverse($parts);
        }

        yield $this->getTag()->setContent(implode(sprintf(' %s ', $options['separator'] ?? '-'),
            $parts));
    }
}
