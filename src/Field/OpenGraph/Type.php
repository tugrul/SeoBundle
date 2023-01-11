<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\FieldData;

class Type extends Base
{
    // possible values are
    // website
    // music.song
    // music.album
    // music.playlist
    // music.radio_station
    // video.movie
    // video.episode
    // video.tv_show
    // video.other
    // article
    // book
    // profile

    function getName(): string
    {
        return 'type';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if ($this->isValidType($content)) {
            yield $this->getTag()->setContent($content);
        }

    }

    public function isValidType(string $typeName): bool
    {
        $target = [
            ['website', 'article', 'book', 'profile'],
            array_map(static function ($type) { return 'video.' . $type; },
                ['movie', 'episode', 'tv_show', 'other'] ),
            array_map(static function ($type) { return 'music.' . $type; },
                ['song', 'album', 'playlist', 'radio_station'] )
        ];

        foreach ($target as $item) {
            if (in_array($typeName, $item, true)) {
                return true;
            }
        }

        return false;
    }
}
