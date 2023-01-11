<?php

namespace Tug\SeoBundle\Field\Music;

use Tug\SeoBundle\Field\FieldData;

class Song extends Base
{
    protected static array $validNames = ['url', 'disc', 'track'];

    function getName(): string
    {
        return 'song';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $song) {
            foreach ($song as $name => $value) {
                if (in_array($name, self::$validNames)) {
                    yield $this->getTag($name !== 'url' ? $name : '')->setContent($value);
                }
            }
        }
    }
}
