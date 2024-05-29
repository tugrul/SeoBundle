<?php

namespace Tug\SeoBundle\Tests\Field;

use Symfony\Component\Serializer\SerializerInterface;
use Tug\SeoBundle\Field\Basic\JsonLd;
use Tug\SeoBundle\Field\FieldData;

class JsonLdFieldTest extends AbstractFieldTest
{
    public function testBasic(): void
    {
        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        $serializer->method('serialize')->willReturnCallback(fn($params) => json_encode($params));

        $field = new JsonLd();
        $field->setSerializer($serializer);

        $this->assertEquals(['jsonLd'], $field->getNamespace());

        $fieldData = new FieldData();

        $fieldData->setContent(['a', 'b', 'c']);

        $scripts = [ ...$field->buildModels($fieldData) ];

        $this->assertCount(1, $scripts);

        $this->assertEquals('application/ld+json', $scripts[0]->getType());

        $this->assertEquals('["a","b","c"]', $scripts[0]->getBody());
    }
}