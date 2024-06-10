<?php

namespace Tug\SeoBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Field\Basic\Description;
use Tug\SeoBundle\Registry\{Context, Field as FieldRegistry, Renderer};
use Tug\SeoBundle\Renderer\{Link, Meta, Title};
use Tug\SeoBundle\Tests\Stub\{Field, FieldTranslatorService, RouteNameProvider};
use Tug\SeoBundle\Translate\{TranslationType, Translator};
use Tug\SeoBundle\Field\Basic\Title as TitleField;
use Tug\SeoBundle\Twig\TugSeoExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TugSeoExtensionTest extends TestCase
{
    protected Environment $twig;

    protected Context $context;

    protected FieldRegistry $field;

    protected RouteNameProvider $routeNameProvider;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $translator = new Translator(new FieldTranslatorService());
        $translator->setType(TranslationType::None);

        $loader = new ArrayLoader([
            'index' => '<!-- start -->{{ tug_seo(null) }}<!-- end -->',
            'index_well_formed' => '<!-- start -->{{ tug_seo(2) }}<!-- end -->',
            'menu_page' => '<a href="#"{% if tug_seo_is_route_active(routeName, skipRoot) %} ' .
                'class="active"{% endif %}>Page</a>'
        ]);

        $this->field = new FieldRegistry();

        $titleField = new TitleField();
        $titleField->setTranslator($translator);
        $this->field->set($titleField);

        $description = new Description();
        $description->setTranslator($translator);
        $this->field->set($description);

        $renderer = new Renderer();
        $renderer->set(new Meta());
        $renderer->set(new Title());
        $renderer->set(new Link());

        $this->context = new Context();

        $this->routeNameProvider = new RouteNameProvider();
        $this->routeNameProvider->setCurrentRouteName('index');

        $this->twig = new Environment($loader);

        $this->twig->addExtension(new TugSeoExtension($this->field, $this->context,
            $renderer, $this->routeNameProvider));
    }

    public function testEmptyContext(): void
    {
        $this->assertEquals('<!-- start --><!-- end -->', $this->twig->render('index'));
    }

    public function testBasicFields(): void
    {
        $this->context->setRouteFields([
            'index' => [
                'title' => 'testing title',
                'description' => 'testing description'
            ]
        ]);

        $this->assertEquals('<!-- start --><title>testing title</title>' .
            '<meta name="description" content="testing description" /><!-- end -->',
            $this->twig->render('index'));

        $this->assertEquals('<!-- start --><title>testing title</title>' . PHP_EOL .
            '  <meta name="description" content="testing description" /><!-- end -->',
            $this->twig->render('index_well_formed'));
    }

    public function testNonExistAndExistsField(): void
    {
        $this->routeNameProvider->setCurrentRouteName('login');

        $this->context->setRouteFields([
            'index' => [ 'dummy' => [ 'field' => 'zaza' ] ],
            'login' => [ 'dummy' => [ 'field' => 'zuzu' ] ]
        ]);

        $this->context->setRouteOptions([
            'index' => [ 'dummy' => [ 'field' => ['option1' => 'oval1'] ] ]
        ]);

        $this->context->setRouteParameters([
            'login' => [ 'dummy' => [ 'field' => ['param1' => 'pval1'] ] ]
        ]);

        $this->context->setHierarchy(['login' => 'index']);

        $this->assertEquals('<!-- start --><!-- end -->', $this->twig->render('index'));

        $this->field->set(new Field());

        $this->assertEquals('<!-- start --><meta name="test1" content="zuzu # param1=pval1" />' .
            '<meta property="test2" content="a # option1=oval1" /><!-- end -->',
            $this->twig->render('index'));
    }

    public function testIsRouteActive(): void
    {
        $this->context->setHierarchy([
            'contact' => 'index',
            'post-index' => 'index',
            'post-detail' => 'post-index',
            'archive' => 'index',
            'archive-year' => 'archive',
            'archive-year-month' => 'archive-year'
        ]);

        $this->routeNameProvider->setCurrentRouteName('post-detail');

        $this->assertEquals('<a href="#">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'non-exists', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'post-detail', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'post-index', 'skipRoot' => true]));

        $this->assertEquals('<a href="#">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'index', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'index', 'skipRoot' => false]));

        $this->routeNameProvider->setCurrentRouteName('archive-year-month');

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'archive-year-month', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'archive-year', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'archive', 'skipRoot' => true]));

        $this->assertEquals('<a href="#">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'index', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'index', 'skipRoot' => false]));

        $this->routeNameProvider->setCurrentRouteName('non-exists');

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'non-exists', 'skipRoot' => true]));

        $this->assertEquals('<a href="#" class="active">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'non-exists', 'skipRoot' => false]));

        $this->assertEquals('<a href="#">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'index', 'skipRoot' => true]));

        $this->assertEquals('<a href="#">Page</a>',
            $this->twig->render('menu_page', ['routeName' => 'other-non-exists', 'skipRoot' => true]));
    }
}
