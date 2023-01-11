<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\{FieldData, TranslatableFieldInterface, TranslatableFieldTrait};

class Description extends Base implements TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    function getName(): string
    {
        return 'description';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();
        $parameters = $fieldData->getParameters();

        yield $this->getTag()->setContent($this->translator->translate($content, $parameters));
    }
}
