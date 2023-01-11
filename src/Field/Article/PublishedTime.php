<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\FieldData;

class PublishedTime extends Base
{
    function getName(): string
    {
        return 'published_time';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
