<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Determiner extends Base
{
    function getName(): string
    {
        return 'determiner';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
