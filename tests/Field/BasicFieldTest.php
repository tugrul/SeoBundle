<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\Basic\{AmpHtml, Canonical, Description, Keywords, Robots, Title};
use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Model\{Link, Meta, Title as TitleModel};

class BasicFieldTest extends AbstractFieldTest
{
    public function testAmpHtml(): void
    {
        $field = new AmpHtml();

        $this->assertEquals(['amphtml'], $field->getNamespace());

        $ampUrl = 'https://example.com/amp-html-test';

        $fieldData = new FieldData();
        $fieldData->setContent($ampUrl);

        /**
         * @type $link Link
         */
        [ $link ] = [ ...$field->buildModels($fieldData) ];

        $this->assertInstanceOf(Link::class, $link);

        $this->assertEquals('amphtml', $link->getRel());

        $this->assertEquals($ampUrl, $link->getHref());
    }

    public function testCanonical(): void
    {
        $field = new Canonical();

        $this->assertEquals(['canonical'], $field->getNamespace());

        $canonicalUrl = 'https://example.com/canonical-test';

        $fieldData = new FieldData();
        $fieldData->setContent($canonicalUrl);

        /**
         * @type $link Link
         */
        [ $link ] = [ ...$field->buildModels($fieldData) ];

        $this->assertInstanceOf(Link::class, $link);

        $this->assertEquals('canonical', $link->getRel());

        $this->assertEquals($canonicalUrl, $link->getHref());
    }

    public function testDescription(): void
    {
        $field = new Description();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['description'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('some description text');
        $fieldData->setParameters([
            'param1' => 'value1',
            'param2' => 'value2'
        ]);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('description', $meta->getName());

        $this->assertEquals('some description text # param1=value1 | param2=value2', $meta->getContent());
    }

    public function testKeywords(): void
    {
        $field = new Keywords();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['keywords'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('some,keyword,text');
        $fieldData->setParameters([
            'param1' => 'value1',
            'param2' => 'value2'
        ]);

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('keywords', $meta->getName());

        $this->assertEquals('some,keyword,text # param1=value1 | param2=value2', $meta->getContent());
    }

    public function testRobots(): void
    {
        $field = new Robots();

        $this->assertEquals(['robots'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('follow');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('robots', $meta->getName());

        $this->assertEquals('follow', $meta->getContent());
    }

    public function testTitle(): void
    {
        $field = new Title();
        $field->setTranslator($this->getTranslator());

        $this->assertEquals(['title'], $field->getNamespace());

        $parent = new FieldData();
        $parent->setContent('homepage');
        $parent->setParameters(['param1' => 'val1', 'param2' => 'val2']);

        $children = new FieldData();
        $children->setParent($parent);
        $children->setContent('children');
        $children->setParameters(['param3' => 'val3', 'param4' => 'val4']);

        /**
         * @type $title1 TitleModel
         * @type $title2 TitleModel
         */
        [ $title1, $title2 ] = [ ...$field->buildModels($children), ...$field->buildModels($parent) ];

        $this->assertInstanceOf(TitleModel::class, $title1);

        $this->assertEquals('children # param3=val3 | param4=val4 - ' .
            'homepage # param1=val1 | param2=val2', $title1->getValue());

        $this->assertInstanceOf(TitleModel::class, $title2);

        $this->assertEquals('homepage # param1=val1 | param2=val2', $title2->getValue());

        $children->setOptions(['separator' => '>']);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4 > ' .
            'homepage # param1=val1 | param2=val2', $title1->getValue());

        $children->setOptions(['reverse' => true]);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('homepage # param1=val1 | param2=val2 > ' .
            'children # param3=val3 | param4=val4', $title1->getValue());

        $children->setOptions(['separator' => '$'], false);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4 $ ' .
            'homepage # param1=val1 | param2=val2', $title1->getValue());

        $children->setParent(null);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getValue());

        $children->setOptions(['reverse' => true, 'separator' => '<']);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getValue());

        $children->setParent($parent);
        $children->setOptions(['single' => true]);

        [ $title1 ] = [ ...$field->buildModels($children) ];

        $this->assertEquals('children # param3=val3 | param4=val4', $title1->getValue());
    }
}
