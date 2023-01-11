<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\Video\{Actor, Director, Duration, ReleaseDate, Tag, Writer};
use Tug\SeoBundle\Model\Meta;

class VideoFieldTest extends AbstractFieldTest
{
    public function testActor(): void
    {
        $field = new Actor();

        $this->assertEquals(['video', 'actor'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('https://example.com/user/someone');

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('video:actor', $metas[0]->getProperty());

        $this->assertEquals('https://example.com/user/someone', $metas[0]->getContent());

        $fieldData->setContent([
            ['role' => 'Driver'],
            ['url' => 'https://example.com/user/kimo'],
            ['url' => 'https://example.com/user/obuo', 'role' => 'Jumper'],
            ['url' => 'https://example.com/user/eboa', 'role' => ['Cat', 'Puma']]
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];
        $this->assertCount(6, $metas);

        $this->assertEquals('video:actor', $metas[0]->getProperty());
        $this->assertEquals('https://example.com/user/kimo', $metas[0]->getContent());

        $this->assertEquals('video:actor', $metas[1]->getProperty());
        $this->assertEquals('https://example.com/user/obuo', $metas[1]->getContent());

        $this->assertEquals('video:actor:role', $metas[2]->getProperty());
        $this->assertEquals('Jumper', $metas[2]->getContent());

        $this->assertEquals('video:actor', $metas[3]->getProperty());
        $this->assertEquals('https://example.com/user/eboa', $metas[3]->getContent());

        $this->assertEquals('video:actor:role', $metas[4]->getProperty());
        $this->assertEquals('Cat', $metas[4]->getContent());

        $this->assertEquals('video:actor:role', $metas[5]->getProperty());
        $this->assertEquals('Puma', $metas[5]->getContent());
    }

    public function testDirector(): void
    {
        $field = new Director();

        $this->assertEquals(['video', 'director'], $field->getNamespace());

        $profiles = [
            'https://example.com/user/zimbo',
            'https://example.com/user/mondo',
            'https://example.com/user/punto'
        ];

        $fieldData = new FieldData();
        $fieldData->setContent($profiles[0]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('video:director', $metas[0]->getProperty());

        $this->assertEquals('https://example.com/user/zimbo', $metas[0]->getContent());

        $fieldData->setContent($profiles);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        foreach ($metas as $index => $meta) {
            $this->assertEquals('video:director', $meta->getProperty());
            $this->assertEquals($profiles[$index], $meta->getContent());
        }
    }

    public function testDuration(): void
    {
        $field = new Duration();

        $this->assertEquals(['video', 'duration'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('2355');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('video:duration', $meta->getProperty());

        $this->assertEquals('2355', $meta->getContent());
    }

    public function testReleaseDate(): void
    {
        $releaseDate = new ReleaseDate();

        $this->assertEquals(['video', 'release_date'], $releaseDate->getNamespace());

        $dateTimeStr = '2023‐08‐31T11:26:34Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$releaseDate->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('video:release_date', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testTag(): void
    {
        $tag = new Tag();

        $this->assertEquals(['video', 'tag'], $tag->getNamespace());

        $tags = ['some_v', 'thing_v', 'other_v', 'great_v', 'thing_v'];

        $fieldData = new FieldData();
        $fieldData->setContent($tags[0]);

        [ $meta ] = [ ...$tag->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('video:tag', $meta->getProperty());

        $this->assertEquals('some_v', $meta->getContent());

        $fieldData = new FieldData();
        $fieldData->setContent($tags);

        $metas = [ ...$tag->buildModels($fieldData) ];

        $this->assertCount(4, $metas);

        foreach ($metas as $index => $meta) {
            $this->assertEquals('video:tag', $meta->getProperty());

            $this->assertEquals($tags[$index], $meta->getContent());
        }
    }

    public function testWriter(): void
    {
        $field = new Writer();

        $this->assertEquals(['video', 'writer'], $field->getNamespace());

        $profiles = [
            'https://example.com/user/zimbo_v',
            'https://example.com/user/mondo_v',
            'https://example.com/user/punto_v'
        ];

        $fieldData = new FieldData();
        $fieldData->setContent($profiles[0]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('video:writer', $metas[0]->getProperty());

        $this->assertEquals('https://example.com/user/zimbo_v', $metas[0]->getContent());

        $fieldData->setContent($profiles);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        foreach ($metas as $index => $meta) {
            $this->assertEquals('video:writer', $meta->getProperty());
            $this->assertEquals($profiles[$index], $meta->getContent());
        }
    }
}
