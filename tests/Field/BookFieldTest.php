<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\Book\{Author, Isbn, ReleaseDate, Tag};
use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Model\Meta;

class BookFieldTest extends FieldTest
{
    public function testAuthor(): void
    {
        $author = new Author();

        $this->assertEquals(['book', 'author'], $author->getNamespace());

        $profileUrls = array_map(fn($name) => 'https://example.com/user/' . $name, [
            'someone', 'otherone', 'thirdone']);

        $fieldData = new FieldData();
        $fieldData->setContent($profileUrls[0]);

        [ $meta ] = [ ...$author->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('book:author', $meta->getProperty());

        $this->assertEquals($profileUrls[0], $meta->getContent());

        $fieldData->setContent($profileUrls);

        $models = $author->buildModels($fieldData);

        foreach ($models as $index => $meta) {
            $this->assertEquals($profileUrls[$index], $meta->getContent());
        }
    }

    public function testIsbn(): void
    {
        $isbn = new Isbn();

        $this->assertEquals(['book', 'isbn'], $isbn->getNamespace());

        $sample = '978-3-16-148410-0';

        $fieldData = new FieldData();
        $fieldData->setContent($sample);

        [ $meta ] = [ ...$isbn->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('book:isbn', $meta->getProperty());

        $this->assertEquals($sample, $meta->getContent());
    }

    public function testReleaseDate(): void
    {
        $releaseDate = new ReleaseDate();

        $this->assertEquals(['book', 'release_date'], $releaseDate->getNamespace());

        $dateTimeStr = '2023‐08‐31T12:10:34Z';

        $fieldData = new FieldData();
        $fieldData->setContent($dateTimeStr);

        [ $meta ] = [ ...$releaseDate->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('book:release_date', $meta->getProperty());

        $this->assertEquals($dateTimeStr, $meta->getContent());
    }

    public function testTag(): void
    {
        $tag = new Tag();

        $this->assertEquals(['book', 'tag'], $tag->getNamespace());

        $tags = ['some', 'thing', 'other', 'great', 'thing'];

        $fieldData = new FieldData();
        $fieldData->setContent($tags[0]);

        [ $meta ] = [ ...$tag->buildModels($fieldData) ];

        $this->assertInstanceOf(Meta::class, $meta);

        $this->assertEquals('book:tag', $meta->getProperty());

        $this->assertEquals('some', $meta->getContent());

        $fieldData = new FieldData();
        $fieldData->setContent($tags);

        $metas = [ ...$tag->buildModels($fieldData) ];

        $this->assertCount(4, $metas);

        foreach ($metas as $index => $meta) {
            $this->assertEquals('book:tag', $meta->getProperty());

            $this->assertEquals($tags[$index], $meta->getContent());
        }
    }
}
