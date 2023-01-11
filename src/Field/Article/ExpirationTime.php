<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\FieldData;

class ExpirationTime extends Base
{
    function getName(): string
    {
        return 'expiration_time';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
