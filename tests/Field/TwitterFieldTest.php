<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\Twitter\{App, Card, Creator, Description, Image, Player, Site, Title};
use Tug\SeoBundle\Model\Meta;

class TwitterFieldTest extends AbstractFieldTest
{
    public function testApp(): void
    {
        $field = new App();

        $this->assertEquals(['twitter', 'app'], $field->getNamespace());

        $data = [
            'country' => 'TR',
            'iphone' => [
                'id' => '929750075',
                'invalid' => 'value',
                'name' => 'Cannonball',
                'url' => 'cannonball://poem/5149e249222f9e600a7540ef'
            ],
            'ipad' => [
                'id' => '123456',
                'name' => 'Number Game',
                'url' => 'number-game://level/13'
            ],
            'googleplay' => [
                'id' => 'com.testing.comolokko',
                'name' => 'Comolokko',
                'url' => 'https://example.com/stage/27'
            ],
            'invalidplatform' => [
                'id' => '5544332',
                'name' => 'Ghost',
                'url' => 'https://example.com/ghost'
            ]
        ];

        $fieldData = new FieldData();
        $fieldData->setContent([...$data]);

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(10, $metas);

        $meta = array_shift($metas);

        $this->assertEquals('twitter:app:country', $meta->getName());
        $this->assertEquals('TR', $meta->getContent());

        foreach ($data as $platform => $properties) {
            if (in_array($platform, ['invalidplatform', 'country'])) {
                continue;
            }

            foreach ($properties as $property => $value) {
                if ($property === 'invalid') {
                    continue;
                }

                $meta = array_shift($metas);
                $handle = 'twitter:app:' . $property . ':' . $platform;
                $this->assertEquals($handle, $meta->getName());
                $this->assertEquals($value, $meta->getContent());
            }
        }
    }

    public function testCard(): void
    {
        $field = new Card();

        $this->assertEquals(['twitter', 'card'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('summary_large_image');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('twitter:card', $meta->getName());
        $this->assertEquals('summary_large_image', $meta->getContent());
    }

    public function testCreator(): void
    {
        $field = new Creator();

        $this->assertEquals(['twitter', 'creator'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('@testing123');

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:creator', $metas[0]->getName());
        $this->assertEquals('@testing123', $metas[0]->getContent());

        $fieldData->setContent(['username' =>'@blablabla']);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:creator', $metas[0]->getName());
        $this->assertEquals('@blablabla', $metas[0]->getContent());

        $fieldData->setContent([
            'username' =>'@zamazingo',
            'id' => '5544332211'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(2, $metas);

        $this->assertEquals('twitter:creator', $metas[0]->getName());
        $this->assertEquals('@zamazingo', $metas[0]->getContent());

        $this->assertEquals('twitter:creator:id', $metas[1]->getName());
        $this->assertEquals('5544332211', $metas[1]->getContent());
    }

    public function testDescription(): void
    {
        $field = new Description();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['twitter', 'description'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('some information');
        $fieldData->setParameters(['param1' => 'value1', 'param2' => 'value2']);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('twitter:description', $meta->getName());

        $this->assertEquals('some information # param1=value1 | param2=value2', $meta->getContent());
    }

    public function testImage(): void
    {
        $field = new Image();

        $this->assertEquals(['twitter', 'image'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('https://example.com/testing123.png');

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:image', $metas[0]->getName());
        $this->assertEquals('https://example.com/testing123.png', $metas[0]->getContent());

        $fieldData->setContent([
            'url' => 'https://example.com/testing345.png'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:image', $metas[0]->getName());
        $this->assertEquals('https://example.com/testing345.png', $metas[0]->getContent());

        $fieldData->setContent([
            'url' => 'https://example.com/image/crazy.jpg',
            'alt' => 'crazy frog'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(2, $metas);

        $this->assertEquals('twitter:image', $metas[0]->getName());
        $this->assertEquals('https://example.com/image/crazy.jpg', $metas[0]->getContent());

        $this->assertEquals('twitter:image:alt', $metas[1]->getName());
        $this->assertEquals('crazy frog', $metas[1]->getContent());
    }

    public function testPlayer(): void
    {
        $field = new Player();

        $this->assertEquals(['twitter', 'player'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('https://example.com/video/funny-pidgeon');

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:player', $metas[0]->getName());
        $this->assertEquals('https://example.com/video/funny-pidgeon', $metas[0]->getContent());

        $fieldData->setContent([
            'url' => 'https://example.com/video/cigubigule'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:player', $metas[0]->getName());
        $this->assertEquals('https://example.com/video/cigubigule', $metas[0]->getContent());

        $fieldData->setContent([
            'url' => 'https://example.com/video/ding-ding-dong-dong',
            'width' => '640', 'height' => '480',
            'stream' => 'https://example.com/video/ding-dong.m3u'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(4, $metas);

        $this->assertEquals('twitter:player', $metas[0]->getName());
        $this->assertEquals('https://example.com/video/ding-ding-dong-dong', $metas[0]->getContent());

        $this->assertEquals('twitter:player:width', $metas[1]->getName());
        $this->assertEquals('640', $metas[1]->getContent());

        $this->assertEquals('twitter:player:height', $metas[2]->getName());
        $this->assertEquals('480', $metas[2]->getContent());

        $this->assertEquals('twitter:player:stream', $metas[3]->getName());
        $this->assertEquals('https://example.com/video/ding-dong.m3u', $metas[3]->getContent());
    }

    public function testSite(): void
    {
        $field = new Site();

        $this->assertEquals(['twitter', 'site'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('@acmeco');

        /**
         * @type $metas Meta[]
         */
        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:site', $metas[0]->getName());
        $this->assertEquals('@acmeco', $metas[0]->getContent());

        $fieldData->setContent(['username' =>'@babafingo']);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $metas);

        $this->assertEquals('twitter:site', $metas[0]->getName());
        $this->assertEquals('@babafingo', $metas[0]->getContent());

        $fieldData->setContent([
            'username' =>'@bilibilibom',
            'id' => '6688224466'
        ]);

        $metas = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(2, $metas);

        $this->assertEquals('twitter:site', $metas[0]->getName());
        $this->assertEquals('@bilibilibom', $metas[0]->getContent());

        $this->assertEquals('twitter:site:id', $metas[1]->getName());
        $this->assertEquals('6688224466', $metas[1]->getContent());
    }

    public function testTitle(): void
    {
        $field = new Title();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['twitter', 'title'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('the lazy penguin');
        $fieldData->setParameters([
            'param1' => 'value1',
            'param2' => 'value2'
        ]);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('twitter:title', $meta->getName());

        $this->assertEquals('the lazy penguin # param1=value1 | param2=value2', $meta->getContent());
    }
}
