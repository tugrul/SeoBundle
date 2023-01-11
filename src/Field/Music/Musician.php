<?php

namespace Tug\SeoBundle\Field\Music;

use Tug\SeoBundle\Field\FieldData;

class Musician extends Base
{
    function getName(): string
    {
        return 'musician';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        foreach ($content as $field) {
            yield $this->getTag()->setContent($field);
        }
    }
}
