<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class Duration extends Base
{
    function getName(): string
    {
        return 'duration';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
        } elseif (is_numeric($content)) {
            yield $this->getTag()->setContent(strval($content));
        }
    }
}
