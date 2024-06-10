<?php

namespace Tug\SeoBundle\Tests\Serializer\Normalizer;

use PHPUnit\Framework\TestCase;

use Tug\SeoBundle\Tests\Stub\JsonLd\Modifier\{NoContextModifier, WithContextModifier};
use Tug\SeoBundle\Tests\Stub\JsonLd\ModifiedType;
use Tug\SeoBundle\Tests\Stub\JsonLd\ModifiedTypeContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface as TranslatorServiceInterface;
use Tug\SeoBundle\JsonLd\Filter\FilterData;
use Tug\SeoBundle\Schema\Enumeration\ItemListOrderType;

use Tug\SeoBundle\Schema\Type\BreadcrumbList;
use Tug\SeoBundle\Schema\Type\ListItem;
use Tug\SeoBundle\Serializer\Normalizer\{JsonLdArrayNormalizer,  JsonLdObjectNormalizer};


use Tug\SeoBundle\Tests\Stub\JsonLd\MixedContextModel;
use Tug\SeoBundle\Tests\Stub\JsonLd\ModelLevel;
use Tug\SeoBundle\Tests\Stub\JsonLd\FilterModel;
use Tug\SeoBundle\Tests\Stub\JsonLd\CollectionModel;


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

        $jsonLdRegistry->setModifier(new WithContextModifier());
        $jsonLdRegistry->setModifier(new NoContextModifier());

        $jsonLdRegistry->setFilter('test', fn() => null);
        $jsonLdRegistry->setFilter('pick_params', fn(FilterData $data) => $data->params);
        $jsonLdRegistry->setFilter('pick_value', fn(FilterData $data) => $data->params['value'] ?? null);
        $jsonLdRegistry->setFilter('array_flip', fn(FilterData $data) => array_flip($data->value));
        $jsonLdRegistry->setFilter('append_str', fn(FilterData $data) => $data->value . ($data->params['suffix'] ?? ''));

        $this->serializer = new Serializer([
            new JsonLdArrayNormalizer($this->getTranslator(), $jsonLdRegistry),
            new JsonLdObjectNormalizer($jsonLdRegistry)
        ]);
    }

    public function testBasic(): void
    {
        $breadcrumb = new BreadcrumbList();

        $breadcrumb->setItemListOrder(ItemListOrderType::Ascending);

        $this->assertEquals([
            '@type' => 'BreadcrumbList',
            '@context' => 'https://schema.org',
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
            '@context' => 'https://schema.org',
            'numberOfItems' => 2,
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    '@context' => 'https://schema.org',
                    'item' => ['@id' => 'https://example.org/foo'],
                    'url' => 'https://example.org/foo'
                ],
                [
                    '@type' => 'ListItem',
                    '@context' => 'https://schema.org',
                    'item' => ['@id' => 'https://example.org/bar'],
                    'url' => 'https://example.org/bar'
                ]
            ]
        ], $this->serializer->normalize($breadcrumb, 'ld+json'));
    }

    public function testIterables(): void
    {
        $item1 = new ListItem();
        $item1->setItem(['@id' => 'https://example.org/foo']);

        $item1->url = 'https://example.org/foo';

        $item2 = new ListItem();
        $item2->setItem(['@id' => 'https://example.org/bar']);

        $item2->url = 'https://example.org/bar';

        $iterator = new \ArrayIterator([$item1, $item2]);

        $this->assertEquals([
            [
                '@type' => 'ListItem',
                '@context' => 'https://schema.org',
                'item' => ['@id' => 'https://example.org/foo'],
                'url' => 'https://example.org/foo'
            ],
            [
                '@type' => 'ListItem',
                '@context' => 'https://schema.org',
                'item' => ['@id' => 'https://example.org/bar'],
                'url' => 'https://example.org/bar'
            ]
        ], $this->serializer->normalize($iterator, 'ld+json'));
    }

    public function testIterablesFilters()
    {
        $model = new CollectionModel();

        $this->assertEquals(['@type' => 'Test', 'zo' => ['1a', '2a', '3a']],
            $this->serializer->normalize($model, 'ld+json'));
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

    public function testReindexKeysWhenNoParameter()
    {
        $list = ['{foo}', 'bar', '{other}'];

        $context = ['jsonLd' => [
            'parameters' => [
                'foo' => null,
                'someParam' => 'Fuu',
                'other' => 'Zabazingo'
            ]
        ]];

        $this->assertEquals(['bar', 'Zabazingo'],
            $this->serializer->normalize($list, 'ld+json', $context));
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

        $this->assertEquals([['@type' => 'ListItem', '@context' => 'https://schema.org', 'item' => 123]],
            $this->serializer->normalize($list, 'ld+json'));
    }

    public function testArrayObjectMultiMixed(): void
    {
        $type = [
            '@type' => 'Fubu',
            'itemList' => [(new ListItem())->setItem(123)],
            'mixedField' => new MixedContextModel()
        ];

        $target = [
            '@type' => 'Fubu',
            'itemList' => [['@type' => 'ListItem', '@context' => 'https://schema.org', 'item' => 123]],
            'mixedField' => [
                '@type' => 'abc',
                'fil1' => 'aaa', 'fil2' => 'bbb', 'fil3' => 'ccc'
            ]
        ];

        $this->assertEquals($target, $this->serializer->normalize($type, 'ld+json'));
    }

    public function testVariableArrayObjectMixed(): void
    {
        $field = new MixedContextModel();

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
        $field = new MixedContextModel();

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
        $field = new ModelLevel();

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

    public function testGenericProperties(): void
    {
        $model = new FilterModel();

        $this->assertEquals([
            '@type' => 'Zoka',
            'myField2' => ['n1' => 'value1', 'n2' => 'value2'],
            'mokoko' => 'abc',
            'chain' => ['abc' => 'f1', 365 => 'f2']
        ], $this->serializer->normalize($model, 'ld+json'));
    }

    public function testModifierWithContext(): void
    {
        $model = new ModifiedTypeContext();

        $target = [
            '@context' => 'https://example.com',
            '@type' => 'ModifiedType',
            'secondField' => 9876,
            'changedField' => 'bbbb',
            'noExistsField' => 1234,
            'nonMappedProp' => 7654
        ];

        $this->assertEquals($target, $this->serializer->normalize($model, 'ld+json'));

        $data = [
            '@context' => 'https://example.com',
            '@type' => 'ModifiedType',
            'existsField' => 'abcd',
            'secondField' => 9876,
            'changedField' => 'aaaa'
        ];

        $this->assertEquals([...$target, 'nonMappedProp' => 7744], $this->serializer->normalize($data, 'ld+json'));
    }

    public function testModifierNoContext(): void
    {
        $model = new ModifiedType();

        $target = [
            '@type' => 'ModifiedType',
            'secondField2' => 9876,
            'changedField2' => 'cccc',
            'noExistsField2' => 5678,
            'nonMappedProp' => 5476
        ];

        $this->assertEquals($target, $this->serializer->normalize($model, 'ld+json'));

        $data = [
            '@type' => 'ModifiedType',
            'existsField2' => 'abcd',
            'secondField2' => 9876,
            'changedField2' => 'aaaa'
        ];

        $this->assertEquals([...$target, 'nonMappedProp' => 9954], $this->serializer->normalize($data, 'ld+json'));
    }
}
