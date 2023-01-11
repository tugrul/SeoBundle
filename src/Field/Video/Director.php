<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class Director extends Base
{
    function getName(): string
    {
        return 'director';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        foreach ($content as $field) {
            if (is_string($field)) {
                yield $this->getTag()->setContent($field);
            }
        }
    }
}
