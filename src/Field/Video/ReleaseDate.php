<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class ReleaseDate extends Base
{
    function getName(): string
    {
        return 'release_date';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
