<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\{FieldData, TranslatableFieldInterface, TranslatableFieldTrait};

class Section extends Base implements TranslatableFieldInterface
{
    use TranslatableFieldTrait;

    function getName(): string
    {
        return 'section';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();
        $parameters = $fieldData->getParameters();

        yield $this->getTag()->setContent($this->translator->translate($content, $parameters));
    }
}
