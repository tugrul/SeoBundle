<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Audio extends Base
{
    protected static array $validNames = ['url', 'secure_url', 'type'];

    function getName(): string
    {
        return 'audio';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $audio) {
            foreach (self::$validNames as $name) {
                if (isset($audio[$name])) {
                    yield $this->getTag($name !== 'url' ? $name : '')->setContent($audio[$name]);
                }
            }
        }
    }
}
