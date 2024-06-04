<?php

namespace Tug\SeoBundle\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Exception\{JsonLdTypeException, JsonLdAttributeException};
use Tug\SeoBundle\JsonLd\Reflection\Attribute as JsonLdAttributeReflector;
use Tug\SeoBundle\Tests\Stub\JsonLd\{DummyEmptyModel,
    DummyMixedContextModel,
    DummyMultiModel,
    DummyMultiTypeFieldModel,
    DummySingleModel,
    DummyValidModel, DummyModelLevel};
use Tug\SeoBundle\JsonLd\Attribute as JsonLdAttribute;
use Tug\SeoBundle\JsonLd\Reflection\{Tag as JsonLdTag, Field as JsonLdField};


class JsonLdAttributeTest extends TestCase
{
    public function testEmptyModelType(): void
    {
        $model = new DummyEmptyModel();
        $reflector = new JsonLdAttributeReflector($model::class);

        $types = $reflector->getTypes();

        $this->assertCount(0, $types);

        $this->assertFalse($reflector->isSingleType());

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('Unable to determine a suitable JsonLd\\Type attribute.');

        $reflector->getType();

        $reflector->getType(['abc']);
    }

    public function testSingleModelType(): void
    {
        $model = new DummySingleModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $types = $reflector->getTypes();

        $this->assertCount(1, $types);

        $this->assertEquals('abc', $types[0]->name);
        $this->assertNull($types[0]->context);

        $this->assertTrue($reflector->isSingleType());

        $type = $reflector->getType();

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $type = $reflector->getType(['abc']);

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('The JsonLd\\Type annotation list does not match the target list.');

        $reflector->getType(['def']);
    }

    public function testDefaultContext(): void
    {
        $model = new DummySingleModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $reflector->setDefaultContext('https://example.com');

        $type = $reflector->getType();

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $type = $reflector->getType(['abc']);

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $type = $reflector->getType([['https://example.com', 'abc']]);

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $reflector->setDefaultContext(null);

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('The JsonLd\\Type annotation list does not match the target list.');

        $reflector->getType([['https://example.com', 'abc']]);
    }

    public function testDefaultContextDefinedModel(): void
    {
        $model = new DummyMixedContextModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $type = $reflector->getType(['abc']);

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $reflector->setDefaultContext('https://example.com');

        $type = $reflector->getType(['abc']);

        $this->assertEquals('abc', $type->name);
        $this->assertNull($type->context);

        $type = $reflector->getType(['def']);

        $this->assertEquals('def', $type->name);
        $this->assertEquals('https://example.com', $type->context);

        $type = $reflector->getType([['https://example.org', 'ghi']]);

        $this->assertEquals('ghi', $type->name);
        $this->assertEquals('https://example.org', $type->context);

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('The JsonLd\\Type annotation list does not match the target list.');

        $reflector->getType(['ghi']);
    }

    public function testGetTags(): void
    {
        $model = new DummyMultiModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $properties = $reflector->getReflector()->getProperties();

        $this->assertCount(5, $properties);

        $fixture = ['aaa' => 'field0', 'bbb' => 'field1', 'ccc' => 'field1',
            'ddd' => 'field3', 'eee' => 'field4'];

        $type = $reflector->getType(['abc']);

        $tags = $reflector->getTags($type, $properties);

        $this->assertCount(3, $tags);

        $this->assertArrayHasKey('aaa', $tags);
        $this->assertArrayHasKey('bbb', $tags);
        $this->assertArrayNotHasKey('ccc', $tags);
        $this->assertArrayNotHasKey('ddd', $tags);
        $this->assertArrayHasKey('eee', $tags);

        foreach ($tags as $key => $tag) {
            $this->assertInstanceOf(JsonLdTag::class, $tag);

            $this->assertEquals($fixture[$key], $tag->field->getName());

            $this->assertEquals($key, $tag->property->name);
        }

        $type = $reflector->getType(['def']);

        $tags = $reflector->getTags($type, $properties);

        $this->assertCount(4, $tags);

        $this->assertArrayHasKey('aaa', $tags);
        $this->assertArrayNotHasKey('bbb', $tags);
        $this->assertArrayHasKey('ccc', $tags);
        $this->assertArrayHasKey('ddd', $tags);
        $this->assertArrayHasKey('eee', $tags);

        foreach ($tags as $key => $tag) {
            $this->assertInstanceOf(JsonLdTag::class, $tag);

            $this->assertEquals($fixture[$key], $tag->field->getName());

            $this->assertEquals($key, $tag->property->name);
        }

        $type = $reflector->getType(['abc']);

        $tags = $reflector->getTags($type, $properties, false);

        $this->assertCount(1, $tags);

        $this->assertArrayNotHasKey('aaa', $tags);
        $this->assertArrayHasKey('bbb', $tags);
        $this->assertArrayNotHasKey('ccc', $tags);
        $this->assertArrayNotHasKey('ddd', $tags);
        $this->assertArrayNotHasKey('eee', $tags);

        foreach ($tags as $key => $tag) {
            $this->assertInstanceOf(JsonLdTag::class, $tag);

            $this->assertEquals($fixture[$key], $tag->field->getName());

            $this->assertEquals($key, $tag->property->name);
        }

        $type = $reflector->getType(['def']);

        $tags = $reflector->getTags($type, $properties, false);

        $this->assertCount(2, $tags);

        $this->assertArrayNotHasKey('aaa', $tags);
        $this->assertArrayNotHasKey('bbb', $tags);
        $this->assertArrayHasKey('ccc', $tags);
        $this->assertArrayHasKey('ddd', $tags);
        $this->assertArrayNotHasKey('eee', $tags);

        foreach ($tags as $key => $tag) {
            $this->assertInstanceOf(JsonLdTag::class, $tag);

            $this->assertEquals($fixture[$key], $tag->field->getName());

            $this->assertEquals($key, $tag->property->name);
        }
    }

    public function testGetTagsMultiMatchException(): void
    {
        $model = new DummyMultiTypeFieldModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $properties = $reflector->getReflector()->getProperties();

        $type = $reflector->getType(['abc']);

        $this->expectException(JsonLdAttributeException::class);
        $this->expectExceptionMessage('Multiple JSON-LD attributes belong to the same type.');

        $reflector->getTags($type, $properties);
    }

    public function testGetterMethods(): void
    {
        $model = new DummyMultiModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $methods = $reflector->getReflector()->getMethods();
        $getters = $reflector->getGetterMethods($methods);

        $this->assertCount(2, $getters);

        $this->assertArrayNotHasKey('protectedSomething', $getters);

        $this->assertArrayHasKey('publicSomething', $getters);

        $this->assertArrayNotHasKey('protectedSame', $getters);

        $this->assertArrayHasKey('publicSame', $getters);

        $this->assertInstanceOf(\ReflectionMethod::class, $getters['publicSomething']);

        $this->assertInstanceOf(\ReflectionMethod::class, $getters['publicSame']);
    }

    public function testFilterAttributes(): void
    {
        $model = new DummyMultiTypeFieldModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $types = $reflector->getTypes();

        $this->assertCount(2, $types);

        $attributes = $reflector->getReflector()->getProperty('field1')
            ->getAttributes(JsonLdAttribute\Property::class);

        $filteredAttributes = $reflector->filterAttributesByType($types[0], $attributes);

        $this->assertCount(2, $filteredAttributes);

        $this->assertEquals('aaa', $filteredAttributes[0]->name);

        $this->assertEmpty($filteredAttributes[0]->owners);

        $this->assertEquals('bbb', $filteredAttributes[1]->name);

        $this->assertEquals(['abc'], $filteredAttributes[1]->owners);

        $filteredAttributes = $reflector->filterAttributesByType($types[0], $attributes, false);

        $this->assertCount(1, $filteredAttributes);

        $this->assertEquals('bbb', $filteredAttributes[0]->name);

        $this->assertEquals(['abc'], $filteredAttributes[0]->owners);

        $filteredAttributes = $reflector->filterAttributesByType($types[1], $attributes);

        $this->assertCount(2, $filteredAttributes);

        $this->assertEquals('aaa', $filteredAttributes[0]->name);

        $this->assertEmpty($filteredAttributes[0]->owners);

        $this->assertEquals('ccc', $filteredAttributes[1]->name);

        $this->assertEquals(['def'], $filteredAttributes[1]->owners);
    }

    public function testMultiModelType(): void
    {
        $model = new DummyMultiModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $types = $reflector->getTypes();

        $this->assertCount(2, $types);

        $this->assertEquals('abc', $types[0]->name);
        $this->assertNull($types[0]->context);

        $this->assertEquals('def', $types[1]->name);
        $this->assertNull($types[1]->context);

        $this->assertFalse($reflector->isSingleType());

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('Multiple JsonLd\\Type annotations detected,' .
            ' but no inference could be made based on the target object.');

        $reflector->getType();
    }

    public function testMultiModelTarget(): void
    {
        $model = new DummyMultiModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $this->expectException(JsonLdTypeException::class);
        $this->expectExceptionMessage('Multiple JsonLd\\Type annotations match the target list.');

        $reflector->getType(['abc', 'def']);
    }


    public function testFilterTypes(): void
    {
        $model = new DummyMixedContextModel();

        $reflector = new JsonLdAttributeReflector($model::class);

        $reflector->setDefaultContext('https://example.org');

        $types = $reflector->getTypes();

        $filtered = $reflector->filterTypes($types, ['abc', ['ghi'], ['https://example.com', 'def']]);

        $this->assertEquals($types, $filtered);

        $filtered = $reflector->filterTypes($types, ['abc', ['https://example.com', 'def']]);

        $this->assertCount(2, $filtered);

        $this->assertEquals('abc', $filtered[0]->name);

        $this->assertNull($filtered[0]->context);

        $this->assertEquals('def', $filtered[1]->name);

        $this->assertEquals('https://example.com', $filtered[1]->context);
    }

    public function testToArray(): void
    {
        $model = new DummyValidModel();

        $model->setVar('varval');

        $reflector = new JsonLdAttributeReflector($model::class);

        $type = $reflector->getType();

        $result = $reflector->toArray($type, $model);

        $this->assertCount(4, $result);

        $fixture = ['@id' => 'https://example.org#valid', 'access' => 'ghi',
            'var' => 'varval', 'mapped' => 123];

        foreach ($fixture as $key => $item) {
            $this->assertArrayHasKey($key, $result);

            $field = $result[$key];

            $this->assertInstanceOf(JsonLdField::class, $field);

            $this->assertEquals($item, $field->value);
        }

        $this->assertEquals(['AnotherValidKind', 'GoodValidKind'], $result['var']->property->types);
    }

    public function testGenericFields(): void
    {
        $reflector = new JsonLdAttributeReflector(DummyModelLevel::class);
        $type = $reflector->getType();

        $toNames = fn($fields) => array_map(fn($field) => $field->name, $fields);

        $this->assertEquals(['gen1', 'gen2', 'gen3'], $toNames($reflector->getGenericProperties($type, 1)));

        $this->assertEquals(['gen1', 'gen3', 'gen4'], $toNames($reflector->getGenericProperties($type, 2)));

        $this->assertEquals(['gen1', 'gen4', 'gen5'], $toNames($reflector->getGenericProperties($type, 3)));

        $this->assertEquals(['gen1', 'gen4', 'gen5'], $toNames($reflector->getGenericProperties($type, 4)));

        $this->assertEquals(['gen1', 'gen4'], $toNames($reflector->getGenericProperties($type, 5)));
    }
}