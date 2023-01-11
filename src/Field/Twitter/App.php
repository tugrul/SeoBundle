<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\FieldData;

class App extends Base
{
    protected static array $platforms = ['iphone', 'ipad', 'googleplay'];
    protected static array $validNames = ['id', 'name', 'url'];

    function getName(): string
    {
        return 'app';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (isset($content['country'])) {
            yield $this->getTag('country')->setContent($content['country']);
        }

        foreach (self::$platforms as $platform) {
            if (empty($content[$platform])) {
                continue;
            }

            foreach (self::$validNames as $name) {
                if (isset($content[$platform][$name])) {
                    yield $this->getTag($name . ':' . $platform)->setContent($content[$platform][$name]);
                }
            }
        }
    }
}
