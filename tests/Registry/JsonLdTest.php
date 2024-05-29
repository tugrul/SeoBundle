<?php

namespace Tug\SeoBundle\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Registry\JsonLd;

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

        $this->assertEmptyArray($registry->getFieldTypes(['https://example.org', 'NonExistType'], 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes(['NonExistType'], 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes('NonExistType', 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes('Foo', 'noField'));

        $this->assertEmptyArray($registry->getFieldTypes(['Foo'], 'noField'));

        $this->assertEquals(['Type1'], $registry->getFieldTypes('Foo', 'field1'));

        $this->assertEquals(['Type2'], $registry->getFieldTypes('Foo', 'field2'));

        $this->assertEquals(['Type3'], $registry->getFieldTypes('Bar', 'field3'));

        $this->assertEquals(['Type5'], $registry->getFieldTypes('Bar', 'field5'));

        $this->assertEquals(['Type11'], $registry->getFieldTypes('Baz', 'field1'));

        $this->assertEquals(['Type2'], $registry->getFieldTypes('Baz', 'field2'));

        $this->assertEquals(['Type33'], $registry->getFieldTypes('Baz', 'field3'));

        $this->assertEquals(['Type44'], $registry->getFieldTypes('Baz', 'field4'));

        $this->assertEquals(['Type5'], $registry->getFieldTypes('Baz', 'field5'));

        $this->assertEquals(['Type111'], $registry->getFieldTypes(['https://example.org', 'Foo'], 'field1'));
    }

    protected function assertEmptyArray($actual, string $message = ''): void
    {
        $this->assertIsArray($actual, $message);
        $this->assertEmpty($actual, $message);
    }
}
