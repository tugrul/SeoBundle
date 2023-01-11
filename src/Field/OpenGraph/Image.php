<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Image extends Base
{
    protected static array $validNames = ['url', 'secure_url', 'type', 'width', 'height', 'alt'];

    function getName(): string
    {
        return 'image';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $image) {
            foreach (self::$validNames as $name) {
                if (isset($image[$name])) {
                    yield $this->getTag($name !== 'url' ? $name : '')->setContent($image[$name]);
                }
            }
        }
    }
}
