<?php

namespace Tug\SeoBundle\Tests\Field;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Model\Meta;

use Tug\SeoBundle\Tests\Stub\Field;

class FieldTest extends TestCase
{
    public function testFieldInterface(): void
    {
        $field = new Field();

        $this->assertEquals(['dummy', 'field'], $field->getNamespace());

        $fieldData1 = new FieldData();
        $fieldData1->setContent(['sample', 'simple content']);
        $fieldData1->setOptions(['some' => 'option1', 'other' => 'option2']);

        $fieldData2 = new FieldData();
        $fieldData2->setParent($fieldData1);
        $fieldData2->setContent('testing123');
        $fieldData2->setParameters(['some1' => 'parameter1', 'other2' => 'parameter2']);

        /**
         * @type $model1 Meta
         * @type $model2 Meta
         */
        [$model1, $model2] = [ ...$field->buildModels($fieldData2) ];

        $this->assertEquals('test1', $model1->getName());
        $this->assertNull($model1->getProperty());
        $this->assertEquals('testing123 # some1=parameter1 | other2=parameter2', $model1->getContent());

        $this->assertEquals('test2', $model2->getProperty());
        $this->assertNull($model2->getName());
        $this->assertEquals('simple content # some=option1 | other=option2', $model2->getContent());
    }
}
