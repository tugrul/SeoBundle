<?php

namespace Tug\SeoBundle\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Registry\Renderer;
use Tug\SeoBundle\Renderer\{Link, Meta, Title};
use Tug\SeoBundle\Tests\Stub\{DummyFaultyModel, DummyFaultyRenderer, DummyModel};
use Tug\SeoBundle\Model\{Title as TitleModel, Link as LinkModel, Meta as MetaModel};

class RendererTest extends TestCase
{
    public function testRegistry(): void
    {
        $renderer = new Renderer();

        $renderer->set(new Meta());
        $renderer->set(new Title());
        $renderer->set(new Link());

        $title = new TitleModel();
        $title->setValue('asdf123');

        $this->assertEquals('<title>asdf123</title>',
            $renderer->render($title));

        $link = new LinkModel();
        $link->setRel('stylesheet');
        $link->setHref('zongi.css');

        $this->assertEquals('<link rel="stylesheet" href="zongi.css" />',
            $renderer->render($link));

        $meta = new MetaModel();
        $meta->setContent('lalalocola');
        $meta->setProperty('zipzip');

        $this->assertEquals('<meta property="zipzip" content="lalalocola" />',
            $renderer->render($meta));

        $notRegistered = new DummyModel();
        $notRegistered->setSomething('hebelehop');

        $this->expectException(\RuntimeException::class);
        $renderer->render($notRegistered);
    }

    public function testFaultyField1(): void
    {
        $renderer = new Renderer();
        $this->expectException(\RuntimeException::class);
        $renderer->set(new DummyFaultyRenderer());
    }

    public function testFaultyField2(): void
    {
        $renderer = new Renderer();
        $this->expectException(\RuntimeException::class);
        $renderer->set(new DummyFaultyRenderer(DummyFaultyModel::class));
    }
}
