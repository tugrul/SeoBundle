<?php

namespace Tug\SeoBundle\Field\Music;

use Tug\SeoBundle\Field\FieldData;

class ReleaseDate extends Base
{
    function getName(): string
    {
        return 'release_date';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        yield $this->getTag()->setContent($content);
    }
}
