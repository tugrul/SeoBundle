<?php

namespace Tug\SeoBundle\Field\Music;

use Tug\SeoBundle\Field\FieldData;

class Album extends Base
{
    protected static array $validNames = ['url', 'disc', 'track'];

    function getName(): string
    {
        return 'album';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        foreach ($content as $album) {

            if (!is_array($album)) {
                continue;
            }

            foreach ($album as $name => $value) {
                if (!in_array($name, self::$validNames)) {
                    continue;
                }


                if ($name !== 'url') {
                    yield $this->getTag($name)->setContent(strval($value));
                    continue;
                }

                if (!is_array($value)) {
                    yield $this->getTag()->setContent($value);
                    continue;
                }

                foreach ($value as $item) {
                    yield $this->getTag()->setContent($item);
                }
            }
        }
    }
}
