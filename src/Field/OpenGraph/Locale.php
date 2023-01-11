<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Locale extends Base
{
    function getName(): string
    {
        return 'locale';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        if (!empty($content['primary'])) {
            yield $this->getTag()->setContent($content['primary']);
        }

        if (empty($content['alternate'])) {
            return;
        }

        if (is_string($content['alternate'])) {
            yield $this->getTag('alternate')->setContent($content['alternate']);
            return;
        }

        if (is_array($content['alternate'])) {
            foreach ($content['alternate'] as $content) {
                yield $this->getTag('alternate')->setContent($content);
            }
        }
    }
}
