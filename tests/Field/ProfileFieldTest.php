<?php

namespace Tug\SeoBundle\Tests\Field;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\Profile\{FirstName, Gender, LastName, Username};
use Tug\SeoBundle\Model\Meta;

class ProfileFieldTest extends AbstractFieldTest
{
    public function testFirstName(): void
    {
        $field = new FirstName();

        $this->assertEquals(['profile', 'first_name'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('John');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('profile:first_name', $meta->getProperty());
        $this->assertEquals('John', $meta->getContent());
    }

    public function testLastName(): void
    {
        $field = new LastName();

        $this->assertEquals(['profile', 'last_name'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('Wick');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('profile:last_name', $meta->getProperty());
        $this->assertEquals('Wick', $meta->getContent());
    }

    public function testGender(): void
    {
        $field = new Gender();

        $this->assertEquals(['profile', 'gender'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('male');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('profile:gender', $meta->getProperty());
        $this->assertEquals('male', $meta->getContent());

        $fieldData->setContent('female');

        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('profile:gender', $meta->getProperty());
        $this->assertEquals('female', $meta->getContent());
    }

    public function testUsername(): void
    {
        $field = new Username();

        $this->assertEquals(['profile', 'username'], $field->getNamespace());

        $fieldData = new FieldData();
        $fieldData->setContent('nicko');

        /**
         * @type $meta Meta
         */
        [ $meta ] = [ ...$field->buildModels($fieldData) ];

        $this->assertEquals('profile:username', $meta->getProperty());
        $this->assertEquals('nicko', $meta->getContent());
    }
}
