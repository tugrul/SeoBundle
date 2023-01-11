<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\FieldData;

class ModifiedTime extends Base
{
    function getName(): string
    {
        return 'modified_time';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
