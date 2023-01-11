<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Video extends Base
{
    protected static array $validNames = ['url', 'secure_url', 'type', 'width', 'height'];

    function getName(): string
    {
        return 'video';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $item) {
            foreach (self::$validNames as $name) {
                if (isset($item[$name])) {
                    yield $this->getTag($name !== 'url' ? $name : '')->setContent($item[$name]);
                }
            }
        }
    }
}
