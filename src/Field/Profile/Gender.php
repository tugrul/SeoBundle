<?php

namespace Tug\SeoBundle\Field\Profile;

use Tug\SeoBundle\Field\FieldData;

class Gender extends Base
{
    function getName(): string
    {
        return 'gender';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
