<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\FieldData;

class Player extends Base
{
    protected static array $validNames = ['url', 'width', 'height', 'stream'];

    function getName(): string
    {
        return 'player';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        foreach (self::$validNames as $name) {
            if (isset($content[$name])) {
                yield $this->getTag($name !== 'url' ? $name : '')->setContent($content[$name]);
            }
        }
    }
}
