<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class Writer extends Base
{
    function getName(): string
    {
        return 'writer';
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
