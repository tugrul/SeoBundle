<?php

namespace Tug\SeoBundle\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Registry\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute\Type as JsonLdType;

class JsonLdTest extends TestCase
{
    public function testBasics()
    {
        $registry = new JsonLd();

        $registry->setTypes([
            [
                'name' => 'Foo',
                'fields' => ['field1' => ['Type1'], 'field2' => ['Type2']]
            ],
            [
                'context' => 'https://example.org', 'name' => 'Foo',
                'fields' => ['field1' => ['Type111']]
            ],
            [
                'name' => 'Bar',
                'fields' => ['field3' => ['Type3'], 'field5' => ['Type5']]
            ],
            [
                'name' => 'Baz', 'parents' => ['Foo', 'Bar'],
                'fields' => ['field1' => ['Type11'], 'field3' => ['Type33'], 'field4' => ['Type44']]
            ]
        ]);

        $this->assertEmptyArray($registry->getFieldTypes(JsonLdType::from(['https://example.org', 'NonExistType']), 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes(JsonLdType::from(['NonExistType']), 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes(JsonLdType::from('NonExistType'), 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes(JsonLdType::from(['Foo']), 'noField'));

        $fooType = JsonLdType::from('Foo');

        $this->assertEmptyArray($registry->getFieldTypes($fooType, 'noField'));

        $this->assertEquals(['Type1'], $registry->getFieldTypes($fooType, 'field1'));

        $this->assertEquals(['Type2'], $registry->getFieldTypes($fooType, 'field2'));

        $barType = JsonLdType::from('Bar');

        $this->assertEquals(['Type3'], $registry->getFieldTypes($barType, 'field3'));

        $this->assertEquals(['Type5'], $registry->getFieldTypes($barType, 'field5'));

        $bazType = JsonLdType::from('Baz');

        $this->assertEquals(['Type11'], $registry->getFieldTypes($bazType, 'field1'));

        $this->assertEquals(['Type2'], $registry->getFieldTypes($bazType, 'field2'));

        $this->assertEquals(['Type33'], $registry->getFieldTypes($bazType, 'field3'));

        $this->assertEquals(['Type44'], $registry->getFieldTypes($bazType, 'field4'));

        $this->assertEquals(['Type5'], $registry->getFieldTypes($bazType, 'field5'));

        $this->assertEquals(['Type111'], $registry->getFieldTypes(JsonLdType::from(['https://example.org', 'Foo']), 'field1'));
    }

    protected function assertEmptyArray($actual, string $message = ''): void
    {
        $this->assertIsArray($actual, $message);
        $this->assertEmpty($actual, $message);
    }
}
