<?php

namespace Tug\SeoBundle\Field\Profile;

use Tug\SeoBundle\Field\FieldData;

class Username extends Base
{
    function getName(): string
    {
        return 'username';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
