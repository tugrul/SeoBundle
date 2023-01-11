<?php

namespace Tug\SeoBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Model\{Link, Meta, ModelInterface, Title};

class DummyModel implements ModelInterface
{
    public static function getHandleName(): string
    {
        return 'dummy_model';
    }
}

class ModelTest extends TestCase
{
    public function testModelInterface(): void
    {
        $this->assertEquals('dummy_model', DummyModel::getHandleName());
    }

    public function testTitleModel(): void
    {
        $this->assertEquals('title', Title::getHandleName());

        $title = new Title();

        $title->setValue('some text');

        $this->assertEquals('some text', $title->getValue());
    }

    public function testMetaModel(): void
    {
        $this->assertEquals('meta', Meta::getHandleName());

        $meta = new Meta();

        $meta->setContent('some content');

        $this->assertEquals('some content', $meta->getContent());

        $meta->setName('some name');

        $this->assertEquals('some name', $meta->getName());

        $meta->setProperty('some property');

        $this->assertEquals('some property', $meta->getProperty());
    }

    public function testLinkModel(): void
    {
        $this->assertEquals('link', Link::getHandleName());

        $link = new Link();

        $link->setHref('https://example.com/path');

        $this->assertEquals('https://example.com/path', $link->getHref());

        $link->setRel('alternate');

        $this->assertEquals('alternate', $link->getRel());

        $link->setType('some type');

        $this->assertEquals('some type', $link->getType());
    }
}
