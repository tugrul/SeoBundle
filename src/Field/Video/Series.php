<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class Series extends Base
{
    function getName(): string
    {
        return 'series';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $field) {
            yield $this->getTag()->setContent($field);
        }
    }
}
