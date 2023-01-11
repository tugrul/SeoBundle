<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\OpenGraph\{Audio, Description, Determiner, Image,
    Locale, SiteName, Title, Type, Url, Video};
use Tug\SeoBundle\Model\Meta;

class OpenGraphFieldTest extends AbstractFieldTest
{
    public function testAudio(): void
    {
        $field = new Audio();

        $this->assertEquals(['og', 'audio'], $field->getNamespace());

        $content = [
            [
                'url' => 'http://example.com/song/blabla.mp3',
                'secure_url' => 'https://example.com/song/blabla.mp3',
                'invalid' => 'value',
                'type' => 'audio/mpeg'
            ],
            [
                'url' => 'http://example.com/song/zamazinga.ogg',
                'invalid' => 'value',
                'secure_url' => 'https://example.com/song/zamazinga.ogg',
                'type' => 'audio/ogg'
            ]
        ];

        $fieldData = new FieldData();
        $fieldData->setContent($content);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(6, $metas);

        foreach ($content as $item) {
            foreach ($item as $key => $value) {
                if ($key === 'invalid') {
                    continue;
                }

                $audio = array_shift($metas);
                $this->assertEquals('og:audio' . ($key === 'url' ? '' : ':' . $key),
                    $audio->getProperty());
                $this->assertEquals($value, $audio->getContent());
            }
        }
    }

    public function testDescription(): void
    {
        $field = new Description();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['og', 'description'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('some information');
        $fieldData->setParameters([
            'param1' => 'value1',
            'param2' => 'value2'
        ]);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('og:description', $meta->getProperty());

        $this->assertEquals('some information # param1=value1 | param2=value2', $meta->getContent());
    }

    public function testDeterminer(): void
    {
        $field = new Determiner();

        $this->assertEquals(['og', 'determiner'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('the');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('og:determiner', $meta->getProperty());

        $this->assertEquals('the', $meta->getContent());
    }

    public function testImage(): void
    {
        $field = new Image();

        $this->assertEquals(['og', 'image'], $field->getNamespace());

        $content = [
            [
                'url' => 'http://example.com/image/comolokko.jpg',
                'secure_url' => 'https://example.com/image/comolokko.jpg',
                'type' => 'image/jpeg', 'invalid' => 'value',
                'width' => '400', 'height' => '300',
                'alt' => 'some good visuality'
            ],
            [
                'url' => 'http://example.com/image/zubizongo.png',
                'secure_url' => 'https://example.com/image/zubizongo.png',
                'type' => 'image/png', 'invalid' => 'value',
                'width' => '1600', 'height' => '900',
                'alt' => 'good colors'
            ]
        ];

        $fieldData = new FieldData();
        $fieldData->setContent($content);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(12, $metas);

        foreach ($content as $item) {
            foreach ($item as $key => $value) {
                if ($key === 'invalid') {
                    continue;
                }

                $audio = array_shift($metas);
                $this->assertEquals('og:image' . ($key === 'url' ? '' : ':' . $key),
                    $audio->getProperty());
                $this->assertEquals($value, $audio->getContent());
            }
        }
    }

    public function testLocale(): void
    {
        $field = new Locale();

        $this->assertEquals(['og', 'locale'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('tr_TR');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('og:locale', $meta->getProperty());
        $this->assertEquals('tr_TR', $meta->getContent());

        $fieldData->setContent([
            'primary' => 'tr_TR',
            'alternate' => 'en_US'
        ]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(2, $metas);

        $this->assertEquals('og:locale', $metas[0]->getProperty());
        $this->assertEquals('tr_TR', $metas[0]->getContent());

        $this->assertEquals('og:locale:alternate', $metas[1]->getProperty());
        $this->assertEquals('en_US', $metas[1]->getContent());

        $fieldData->setContent([
            'primary' => 'en_US',
            'alternate' => ['de_DE', 'en_GB']
        ]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(3, $metas);

        $this->assertEquals('og:locale', $metas[0]->getProperty());
        $this->assertEquals('en_US', $metas[0]->getContent());

        $this->assertEquals('og:locale:alternate', $metas[1]->getProperty());
        $this->assertEquals('de_DE', $metas[1]->getContent());

        $this->assertEquals('og:locale:alternate', $metas[2]->getProperty());
        $this->assertEquals('en_GB', $metas[2]->getContent());
    }

    public function testSiteName(): void
    {
        $field = new SiteName();

        $this->assertEquals(['og', 'site_name'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('Great Website');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('og:site_name', $meta->getProperty());
        $this->assertEquals('Great Website', $meta->getContent());
    }

    public function testTitle(): void
    {
        $field = new Title();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['og', 'title'], $field->getNamespace());

        $parent = new FieldData();
        $parent->setContent('homepage');
        $parent->setParameters(['param1' => 'val1', 'param2' => 'val2']);

        $children = new FieldData();
        $children->setParent($parent);
        $children->setContent('children');
        $children->setParameters(['param3' => 'val3', 'param4' => 'val4']);

        /**
         * @type $title1 Meta
         * @type $title2 Meta
         */
        [ $title1, $title2 ] = [ ...$field->buildModels($children), ...$field->buildModels($parent) ];

        $this->assertInstanceOf(Meta::class, $title1);
        $this->assertEquals('og:title', $title1->getProperty());

        $this->assertEquals('children # param3=val3 | param4=val4 - ' .
            'homepage # param1=val1 | param2=val2', $title1->getContent());

        $this->assertInstanceOf(Meta::class, $title2);
        $this->assertEquals('og:title', $title2->getProperty());

        $this->assertEquals('homepage # param1=val1 | param2=val2', $title2->getContent());

        $children->setOptions(['separator' => '>']);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4 > ' .
            'homepage # param1=val1 | param2=val2', $title1->getContent());

        $children->setOptions(['reverse' => true]);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('homepage # param1=val1 | param2=val2 > ' .
            'children # param3=val3 | param4=val4', $title1->getContent());

        $children->setOptions(['separator' => '$'], false);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4 $ ' .
            'homepage # param1=val1 | param2=val2', $title1->getContent());

        $children->setParent(null);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getContent());

        $children->setOptions(['reverse' => true, 'separator' => '<']);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getContent());

        $children->setParent($parent);
        $children->setOptions(['single' => true]);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getContent());
    }

    public function testType(): void
    {
        $field = new Type();

        $this->assertEquals(['og', 'type'], $field->getNamespace());

        $types = [
            'website','article', 'book', 'profile',
            'music.song', 'music.album', 'music.playlist', 'music.radio_station',
            'invalid',
            'video.movie', 'video.episode', 'video.tv_show', 'video.other'
        ];

        $fieldData = new FieldData();

        foreach ($types as $type) {
            $fieldData->setContent($type);

            /**
             * @type $metas Meta[]
             */
            $metas = [ ...$field->buildModels($fieldData) ];

            if ($type === 'invalid') {
                $this->assertCount(0, $metas);
                continue;
            }

            $this->assertEquals('og:type', $metas[0]->getProperty());
            $this->assertEquals($type, $metas[0]->getContent());
        }
    }

    public function testUrl(): void
    {
        $field = new Url();

        $this->assertEquals(['og', 'url'], $field->getNamespace());

        $url = 'https://example.com/sample-url';

        $fieldData = new FieldData();
        $fieldData->setContent($url);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('og:url', $meta->getProperty());
        $this->assertEquals($url, $meta->getContent());
    }

    public function testVideo(): void
    {
        $field = new Video();

        $this->assertEquals(['og', 'video'], $field->getNamespace());

        $content = [
            [
                'url' => 'http://example.com/movie/zirzavat.mpg',
                'secure_url' => 'https://example.com/movie/zirzavat.mpg',
                'invalid' => 'value',
                'type' => 'video/mpeg',
                'width' => '640', 'height' => '480'
            ],
            [
                'url' => 'http://example.com/movie/labalippo.ogv',
                'invalid' => 'value',
                'secure_url' => 'https://example.com/movie/labalippo.ogv',
                'type' => 'video/ogg',
                'width' => '1280', 'height' => '720'
            ]
        ];

        $fieldData = new FieldData();
        $fieldData->setContent($content);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(10, $metas);

        foreach ($content as $item) {
            foreach ($item as $key => $value) {
                if ($key === 'invalid') {
                    continue;
                }

                $audio = array_shift($metas);
                $this->assertEquals('og:video' . ($key === 'url' ? '' : ':' . $key),
                    $audio->getProperty());
                $this->assertEquals($value, $audio->getContent());
            }
        }
    }
}
