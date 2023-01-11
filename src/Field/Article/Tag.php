<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\FieldData;

class Tag extends Base
{
    function getName(): string
    {
        return 'tag';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        $content = array_unique($content);

        foreach ($content as $field) {
            yield $this->getTag()->setContent($field);
        }
    }
}
