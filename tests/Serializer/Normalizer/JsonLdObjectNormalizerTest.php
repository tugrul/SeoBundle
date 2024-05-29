<?php

namespace Tug\SeoBundle\Tests\Serializer\Normalizer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface as TranslatorServiceInterface;
use Tug\SeoBundle\Schema\Enumeration\ItemListOrderType;

use Tug\SeoBundle\Schema\Type\BreadcrumbList;
use Tug\SeoBundle\Schema\Type\ListItem;
use Tug\SeoBundle\Serializer\Normalizer\{JsonLdArrayNormalizer,  JsonLdObjectNormalizer};


use Tug\SeoBundle\Tests\Stub\JsonLd\DummyMixedContextModel;
use Tug\SeoBundle\Tests\Stub\JsonLd\DummyModelLevel;
use Tug\SeoBundle\Translate\TranslationType;
use Tug\SeoBundle\Translate\Translator;
use Tug\SeoBundle\Translate\TranslatorInterface;
use Tug\SeoBundle\Registry\JsonLd as JsonLdRegistry;

class JsonLdObjectNormalizerTest extends TestCase
{
    protected Serializer $serializer;

    protected function getTranslator(): TranslatorInterface
    {
        $service = $this->createMock(TranslatorServiceInterface::class);

        $callback = fn(string $text, array $parameters = [])
            => preg_replace_callback('/{(\w+)}/',
                fn($matches) => $parameters[$matches[1]] ?? $matches[0], $text);

        $service->method('getLocale')->willReturn('en');
        $service->method('trans')->willReturnCallback($callback);

        $translator = new Translator($service);

        $translator->setType(TranslationType::Icu);
        $translator->setDomain('tug_seo');

        return $translator;
    }

    protected function setUp(): void
    {
        $jsonLdRegistry = new JsonLdRegistry();

        $jsonLdRegistry->setTypes([
            ['name' => 'Fubu',
            'fields' => ['mixedField' => ['abc']]]
        ]);

        $this->serializer = new Serializer([
            new JsonLdArrayNormalizer($this->getTranslator(), $jsonLdRegistry),
            new JsonLdObjectNormalizer()
        ]);
    }

    public function testBasic(): void
    {
        $breadcrumb = new BreadcrumbList();

        $breadcrumb->setItemListOrder(ItemListOrderType::Ascending);

        $this->assertEquals([
            '@type' => 'BreadcrumbList',
            'numberOfItems' => 0,
            'itemListOrder' => 'https://schema.org/ItemListOrderAscending'
        ], $this->serializer->normalize($breadcrumb, 'ld+json'));
    }

    public function testItems(): void
    {
        $breadcrumb = new BreadcrumbList();


        $item = new ListItem();
        $item->setItem(['@id' => 'https://example.org/foo']);
        $breadcrumb->addItem($item);

        $item->url = 'https://example.org/foo';

        $item = new ListItem();
        $item->setItem(['@id' => 'https://example.org/bar']);
        $breadcrumb->addItem($item);

        $item->url = 'https://example.org/bar';

        $this->assertEquals([
            '@type' => 'BreadcrumbList',
            'numberOfItems' => 2,
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'item' => ['@id' => 'https://example.org/foo'],
                    'url' => 'https://example.org/foo'
                ],
                [
                    '@type' => 'ListItem',
                    'item' => ['@id' => 'https://example.org/bar'],
                    'url' => 'https://example.org/bar'
                ]
            ]
        ], $this->serializer->normalize($breadcrumb, 'ld+json'));
    }

    public function testTranslateAssocField(): void
    {
        $thing = [
            '@type' => 'Thing',
            '$name' => 'Its name is {itsName}'
        ];

        $context = ['jsonLd' => [ 'parameters' => [ 'itsName' => 'Ceku' ]]];

        $this->assertEquals([
            '@type' => 'Thing',
            'name' => 'Its name is Ceku'
        ], $this->serializer->normalize($thing, 'ld+json', $context));
    }

    public function testExpandTranslateStringField(): void
    {
        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => 'Zaba {someParam} zingo {otherParam}'
            ]
        ]];

        $this->assertEquals(['foo', 'bar', 'Zaba {someParam} zingo {otherParam}'],
            $this->serializer->normalize(['foo', 'bar', '{other}'], 'ld+json', $context));

        $this->assertEquals(['foo', 'bar', 'Zaba Fuu zingo {otherParam}'],
            $this->serializer->normalize(['foo', 'bar', '<other>'], 'ld+json', $context));
    }

    public function testExpandStringField(): void
    {
        $list = ['foo', 'bar', '{other}'];

        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => 'Zabazingo'
            ]
        ]];

        $this->assertEquals(['foo', 'bar', 'Zabazingo'],
            $this->serializer->normalize($list, 'ld+json', $context));
    }

    public function testExpandArrayField(): void
    {
        $list = ['foo', 'bar', '{other}'];

        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => ['baz', 'raz']
            ]
        ]];

        $this->assertEquals(['foo', 'bar', ['baz', 'raz']],
            $this->serializer->normalize($list, 'ld+json', $context));
    }

    public function testExpandArrayExtendField(): void
    {
        $list = ['foo', 'bar', '<other>'];

        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => ['baz', 'raz']
            ]
        ]];

        $this->assertEquals(['foo', 'bar', 'baz', 'raz'],
            $this->serializer->normalize($list, 'ld+json', $context));
    }

    public function testExpandAssocArrayExtendField(): void
    {
        $list = ['name' => 'foo', 'nick' => 'bar', 'zonk' => '{other}'];

        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => ['baz', 'raz']
            ]
        ]];

        $this->assertEquals(['name' => 'foo', 'nick' => 'bar', 'zonk' => ['baz', 'raz']],
            $this->serializer->normalize($list, 'ld+json', $context));
    }

    public function testExpandAssocArrayExtend2Field(): void
    {
        $list = ['name' => 'foo', 'nick' => 'bar', '<other>'];

        $context = ['jsonLd' => [
            'parameters' => [
                'someParam' => 'Fuu',
                'other' => ['fog' => 'baz', 'fug' => 'raz']
            ]
        ]];

        $this->assertEquals(['name' => 'foo', 'nick' => 'bar', 'fog' => 'baz', 'fug' => 'raz'],
            $this->serializer->normalize($list, 'ld+json', $context));
    }

    public function testArrayObjectMixed(): void
    {
        $list = [(new ListItem())->setItem(123)];

        $this->assertEquals([['@type' => 'ListItem', 'item' => 123]],
            $this->serializer->normalize($list, 'ld+json'));
    }

    public function testArrayObjectMultiMixed(): void
    {
        $type = [
            '@type' => 'Fubu',
            'itemList' => [(new ListItem())->setItem(123)],
            'mixedField' => new DummyMixedContextModel()
        ];

        $target = [
            '@type' => 'Fubu',
            'itemList' => [['@type' => 'ListItem', 'item' => 123]],
            'mixedField' => [
                '@type' => 'abc',
                'fil1' => 'aaa', 'fil2' => 'bbb', 'fil3' => 'ccc'
            ]
        ];

        $this->assertEquals($target, $this->serializer->normalize($type, 'ld+json'));
    }

    public function testVariableArrayObjectMixed(): void
    {
        $field = new DummyMixedContextModel();

        $type = [
            '@type' => 'Fubu',
            'mixedField' => '{fieldVar}'
        ];

        $context = ['jsonLd' => [
            'parameters' => [
                'fieldVar' => $field
            ]
        ]];

        $target = ['@type' => 'Fubu', 'mixedField' => [
            '@type' => 'abc',
            'fil1' => 'aaa', 'fil2' => 'bbb', 'fil3' => 'ccc'
        ]];

        $this->assertEquals($target, $this->serializer->normalize($type, 'ld+json', $context));
    }

    public function testVariableExpandObjectMixed(): void
    {
        $field = new DummyMixedContextModel();

        $type = [
            '@type' => 'Fubu',
            'mixedField' => ['<fieldVar>', '@type' => 'Zaa']
        ];

        $context = ['jsonLd' => [
            'parameters' => [
                'fieldVar' => $field
            ]
        ]];

        $target = ['@type' => 'Fubu', 'mixedField' => [
            '@type' => 'Zaa',
            'fil1' => 'aaa', 'fil2' => 'bbb', 'fil3' => 'ccc'
        ]];

        $this->assertEquals($target, $this->serializer->normalize($type, 'ld+json', $context));
    }

    public function testFieldLevel(): void
    {
        $field = new DummyModelLevel();

        $this->assertEquals(['@type' => 'LevelModel', 'aaa' => 'abc123', 'bbb' => 'def456', 'ccc' => 'how is this?'],
            $this->serializer->normalize($field, 'ld+json', ['jsonLd' => [
                'level' => 1
            ]]));

        $this->assertEquals(['@type' => 'LevelModel', 'bbb' => 'def456', 'ccc' => 'how is this?', 'ddd' => 'mmm'],
            $this->serializer->normalize($field, 'ld+json', ['jsonLd' => [
                'level' => 2
            ]]));

        $this->assertEquals(['@type' => 'LevelModel', 'ccc' => 'how is this?', 'ddd' => 'mmm', 'eee' => 'nnn'],
            $this->serializer->normalize($field, 'ld+json', ['jsonLd' => [
                'level' => 3
            ]]));

        $this->assertEquals(['@type' => 'LevelModel', 'ddd' => 'mmm', 'eee' => 'nnn'],
            $this->serializer->normalize($field, 'ld+json', ['jsonLd' => [
                'level' => 4
            ]]));

        $this->assertEquals(['@type' => 'LevelModel', 'ddd' => 'mmm'],
            $this->serializer->normalize($field, 'ld+json', ['jsonLd' => [
                'level' => 5
            ]]));
    }
}
