<?php

namespace Tug\SeoBundle\Field\Book;

use Tug\SeoBundle\Field\FieldData;

class Author extends Base
{
    protected function getName(): string
    {
        return 'author';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        foreach ($content as $field) {
            yield $this->getTag()->setContent($field);
        }
    }
}
