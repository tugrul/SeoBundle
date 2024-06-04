<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('abc')]
#[JsonLd\Type('def')]
class DummyMultiTypeFieldModel
{
    #[JsonLd\Property('aaa')]
    #[JsonLd\Property('bbb', owners: 'abc')]
    #[JsonLd\Property('ccc', owners: 'def')]
    public string $field1;
}
