<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\Article\{Author, ExpirationTime, ModifiedTime, PublishedTime, Section, Tag};
use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Model\Meta;

class ArticleFieldTest extends AbstractFieldTest
{
    public function testAuthor(): void
    {
        $author = new Author();

        $this->assertEquals(['article', 'author'], $author->getNamespace());

        $profileUrls = array_map(fn($name) => 'https://example.com/user/' . $name, [
            'someone', 'otherone', 'thirdone']);

        $fieldData = new FieldData();
        $fieldData->setContent($profileUrls[0]);

        [ $meta ] = [ ...$author->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:author', $meta->getProperty());

        $this->assertEquals($profileUrls[0], $meta->getContent());

        $fieldData->setContent($profileUrls);

        $models = $author->buildModels($fieldData);

        foreach ($models as $index => $meta) {
            $this->assertEquals($profileUrls[$index], $meta->getContent());
        }
    }

    public function testExpirationTime(): void
    {
        $expirationTime = new ExpirationTime();

        $this->assertEquals(['article', 'expiration_time'], $expirationTime->getNamespace());

        $dateTimeStr = '2023‐08‐31T12:10:34Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$expirationTime->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:expiration_time', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testModifiedTime(): void
    {
        $modifiedTime = new ModifiedTime();

        $this->assertEquals(['article', 'modified_time'], $modifiedTime->getNamespace());

        $dateTimeStr = '2023‐08‐30T08:10:34Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$modifiedTime->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:modified_time', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testPublishedTime(): void
    {
        $publishedTime = new PublishedTime();

        $this->assertEquals(['article', 'published_time'], $publishedTime->getNamespace());

        $dateTimeStr = '2023‐08‐28T15:12:26Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$publishedTime->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:published_time', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testSection(): void
    {
        $section = new Section();
        $section->setTranslator($this->getTranslator());

        $this->assertEquals(['article', 'section'], $section->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('art');
        $fieldData->setParameters(['param1' => 'value1', 'param2' => 'value2']);

        [ $meta ] = [ ...$section->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:section', $meta->getProperty());

        $this->assertEquals('art # param1=value1 | param2=value2', $meta->getContent());
    }

    public function testTag(): void
    {
        $tag = new Tag();

        $this->assertEquals(['article', 'tag'], $tag->getNamespace());

        $tags = ['some', 'thing', 'other', 'great', 'thing'];

        $fieldData = new FieldData();
        $fieldData->setContent($tags[0]);

        [ $meta ] = [ ...$tag->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('article:tag', $meta->getProperty());

        $this->assertEquals('some', $meta->getContent());

        $fieldData = new FieldData();
        $fieldData->setContent($tags);

        $metas = [ ...$tag->buildModels($fieldData) ];

        $this->assertCount(4, $metas);

        foreach ($metas as $index => $meta) {
            $this->assertEquals('article:tag', $meta->getProperty());

            $this->assertEquals($tags[$index], $meta->getContent());
        }
    }
}
