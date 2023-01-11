<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\Music\{Album, Creator, Duration, Musician, ReleaseDate, Song};
use Tug\SeoBundle\Model\Meta;

class MusicFieldTest extends AbstractFieldTest
{
    public function testAlbum(): void
    {
        $album = new Album();

        $this->assertEquals(['music', 'album'], $album->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent([
            [
                'url' => 'https://example.com/album/some-good-thing',
                'disc' => 5, 'track' => 2
            ],
            [
                'url' => [
                    'https://example.com/album/comolokko',
                    'https://example.com/album/babazinga'
                ],
                'disc' => 1, 'track' => 1
            ]
        ]);

        $metas = [ ...$album->buildModels($fieldData) ];

        foreach ($metas as $meta) {
            $this->assertInstanceOf(Meta::class, $meta);
        }

        $this->assertCount(7, $metas);

        $this->assertEquals('music:album', $metas[0]->getProperty());
        $this->assertEquals('https://example.com/album/some-good-thing', $metas[0]->getContent());

        $this->assertEquals('music:album:disc', $metas[1]->getProperty());
        $this->assertEquals('5', $metas[1]->getContent());

        $this->assertEquals('music:album:track', $metas[2]->getProperty());
        $this->assertEquals('2', $metas[2]->getContent());

        $this->assertEquals('music:album', $metas[3]->getProperty());
        $this->assertEquals('https://example.com/album/comolokko', $metas[3]->getContent());

        $this->assertEquals('music:album', $metas[4]->getProperty());
        $this->assertEquals('https://example.com/album/babazinga', $metas[4]->getContent());

        $this->assertEquals('music:album:disc', $metas[5]->getProperty());
        $this->assertEquals('1', $metas[5]->getContent());

        $this->assertEquals('music:album:track', $metas[6]->getProperty());
        $this->assertEquals('1', $metas[6]->getContent());

    }

    public function testCreator(): void
    {
        $creator = new Creator();

        $this->assertEquals(['music', 'creator'], $creator->getNamespace());

        $profileUrls = array_map(fn($name) => 'https://example.com/user/' . $name, [
            'someone_c', 'otherone_c', 'thirdone_c']);

        $fieldData = new FieldData();
        $fieldData->setContent($profileUrls[0]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$creator->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('music:creator', $metas[0]->getProperty());
        $this->assertEquals($profileUrls[0], $metas[0]->getContent());

        $fieldData->setContent($profileUrls);

        $metas = [ ...$creator->buildModels($fieldData) ];

        $this->assertCount(3, $metas);

        foreach ($metas as $index => $meta) {
            $this->assertEquals('music:creator', $meta->getProperty());
            $this->assertEquals($profileUrls[$index], $meta->getContent());
        }
    }

    public function testDuration(): void
    {
        $duration = new Duration();

        $this->assertEquals(['music', 'duration'], $duration->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent(210);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$duration->buildModels($fieldData) ];

        $this->assertEquals('music:duration', $meta->getProperty());

        $this->assertEquals('210', $meta->getContent());
    }

    public function testMusician(): void
    {
        $musician = new Musician();

        $this->assertEquals(['music', 'musician'], $musician->getNamespace());

        $profileUrls = array_map(fn($name) => 'https://example.com/user/' . $name, [
            'someone_m', 'otherone_m', 'thirdone_m']);

        $fieldData = new FieldData();
        $fieldData->setContent($profileUrls[0]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$musician->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('music:musician', $metas[0]->getProperty());
        $this->assertEquals($profileUrls[0], $metas[0]->getContent());

        $fieldData->setContent($profileUrls);

        $metas = [ ...$musician->buildModels($fieldData) ];

        $this->assertCount(3, $metas);

        foreach ($metas as $index => $meta) {
            $this->assertEquals('music:musician', $meta->getProperty());
            $this->assertEquals($profileUrls[$index], $meta->getContent());
        }
    }

    public function testReleaseDate(): void
    {
        $releaseDate = new ReleaseDate();

        $this->assertEquals(['music', 'release_date'], $releaseDate->getNamespace());

        $dateTimeStr = '2023‐08‐31T09:07:34Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$releaseDate->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('music:release_date', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testSong(): void
    {
        $song = new Song();

        $this->assertEquals(['music', 'song'], $song->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent([
            [ 'url' => 'https://example.com/song/blabla', 'disc' => 2, 'track' => 3 ],
            [ 'url' => 'https://example.com/song/zibizo', 'disc' => 4, 'track' => 5 ]
        ]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$song->buildModels($fieldData) ];

        $this->assertCount(6, $metas);

        $this->assertEquals('music:song', $metas[0]->getProperty());
        $this->assertEquals('https://example.com/song/blabla', $metas[0]->getContent());

        $this->assertEquals('music:song:disc', $metas[1]->getProperty());
        $this->assertEquals('2', $metas[1]->getContent());

        $this->assertEquals('music:song:track', $metas[2]->getProperty());
        $this->assertEquals('3', $metas[2]->getContent());

        $this->assertEquals('music:song', $metas[3]->getProperty());
        $this->assertEquals('https://example.com/song/zibizo', $metas[3]->getContent());

        $this->assertEquals('music:song:disc', $metas[4]->getProperty());
        $this->assertEquals('4', $metas[4]->getContent());

        $this->assertEquals('music:song:track', $metas[5]->getProperty());
        $this->assertEquals('5', $metas[5]->getContent());
    }
}
