<?php

namespace Tug\SeoBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Model\{Title as TitleModel, Link as LinkModel, Meta as MetaModel};
use Tug\SeoBundle\Renderer\{Link, Meta, Title};
use Tug\SeoBundle\Tests\Stub\DummyRenderer;

class RendererTest extends TestCase
{
    public function testRendererInterface(): void
    {
        $renderer = new DummyRenderer();

        $model = new TitleModel();
        $model->setValue('blabla123');

        $this->assertEquals('<!-- blabla123 -->', $renderer->render($model));
    }

    public function testLink(): void
    {
        $renderer = new Link();

        $model = new LinkModel();
        $model->setRel('stylesheet');
        $model->setHref('/theme/main.css');

        $this->assertInstanceOf($renderer->getModel(), $model);

        $this->assertEquals('<link rel="stylesheet" href="/theme/main.css" />',
            $renderer->render($model));

        $model->setType('text/css');

        $this->assertEquals('<link rel="stylesheet" href="/theme/main.css" type="text/css" />',
            $renderer->render($model));
    }

    public function testMeta(): void
    {
        $renderer = new Meta();

        $model = new MetaModel();

        $this->assertInstanceOf($renderer->getModel(), $model);

        $this->assertEmpty($renderer->render($model));

        $model->setName('testing');

        $this->assertEquals('<meta name="testing" />',
            $renderer->render($model));

        $model->setContent('contento');

        $this->assertEquals('<meta name="testing" content="contento" />',
            $renderer->render($model));

        $model->setProperty('properto');

        $this->assertEquals('<meta name="testing" property="properto" content="contento" />',
            $renderer->render($model));

        $model = new MetaModel();
        $model->setProperty('zibizingo');

        $this->assertEquals('<meta property="zibizingo" />',
            $renderer->render($model));

        $model->setContent('hebeleblo');

        $this->assertEquals('<meta property="zibizingo" content="hebeleblo" />',
            $renderer->render($model));

        $model = new MetaModel();
        $model->setContent('lambalimbo');

        $this->assertEquals('<meta content="lambalimbo" />',
            $renderer->render($model));
    }

    public function testTitle(): void
    {
        $renderer = new Title();

        $model = new TitleModel();

        $this->assertInstanceOf($renderer->getModel(), $model);

        $this->assertEmpty($renderer->render($model));

        $model->setValue('white lion');

        $this->assertEquals('<title>white lion</title>', $renderer->render($model));
    }
}
