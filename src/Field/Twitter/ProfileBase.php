<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\FieldData;

abstract class ProfileBase extends Base
{
    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        if (empty($content['username'])) {
            return;
        }

        yield $this->getTag()->setContent($content['username']);

        if (!empty($content['id'])) {
            yield $this->getTag('id')->setContent($content['id']);
        }
    }
}
