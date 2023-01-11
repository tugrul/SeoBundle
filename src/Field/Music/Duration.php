<?php

namespace Tug\SeoBundle\Field\Music;

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

        yield $this->getTag()->setContent(strval($content));
    }
}
