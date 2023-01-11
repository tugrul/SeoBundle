<?php

namespace Tug\SeoBundle\Field\Book;

use Tug\SeoBundle\Field\FieldData;

class Isbn extends Base
{
    protected function getName(): string
    {
        return 'isbn';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
