<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('ModifiedType')]
class ModifiedType
{
    #[JsonLd\Property('existsField2')]
    public string $f1 = 'abcd';

    #[JsonLd\Property('secondField2')]
    public int $f2 = 9876;

    #[JsonLd\Property('changedField2')]
    public string $f3 = 'aaaa';

    public int $nonMapped2 = 5476;
}