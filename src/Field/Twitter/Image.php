<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\FieldData;

class Image extends Base
{
    function getName(): string
    {
        return 'image';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        if (empty($content['url'])) {
            return;
        }

        yield $this->getTag()->setContent($content['url']);

        if (!empty($content['alt'])) {
            yield $this->getTag('alt')->setContent($content['alt']);
        }
    }
}
