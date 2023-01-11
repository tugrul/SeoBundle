<?php

namespace Tug\SeoBundle\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Field\FieldInterface;
use Tug\SeoBundle\Registry\Field;
use Tug\SeoBundle\Tests\Stub\DummyRegistryField;

class FieldTest extends TestCase
{
    public function testGetField(): void
    {
        $registry = new Field();

        $registry->set(new DummyRegistryField(['og', 'title']));

        $field = $registry->get(['og', 'title']);

        $this->assertNotNull($field);

        $this->assertInstanceOf(FieldInterface::class, $field);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('There is no field defined for namespace non:exists');

        $registry->get(['non', 'exists']);
    }

    public function testGetAllFields(): void
    {
        $registry = new Field();

        $target = [
            ['zingi', 'zonga'],
            ['simba', 'zombo'],
            ['bimbo', 'fimpa']
        ];

        foreach ($target as $namespace) {
            $registry->set(new DummyRegistryField($namespace));
        }

        $fields = $registry->getAll();

        $this->assertCount(count($target), $fields);

        foreach ($target as $index => $namespace) {
            $handle = implode(':', $namespace);
            $this->assertEquals($namespace, $fields[$handle]->getNamespace());
        }
    }
}
