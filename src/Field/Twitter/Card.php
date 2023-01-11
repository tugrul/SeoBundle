<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\FieldData;

class Card extends Base
{
    // 	The card type, which will be one of “summary”, “summary_large_image”, “app”, or “player”.

    function getName(): string
    {
        return 'card';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        yield $this->getTag()->setContent($fieldData->getContent());
    }
}
