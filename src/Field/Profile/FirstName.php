<?php

namespace Tug\SeoBundle\Field\Profile;

use Tug\SeoBundle\Field\FieldData;

class FirstName extends Base
{
    function getName(): string
    {
        return 'first_name';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
