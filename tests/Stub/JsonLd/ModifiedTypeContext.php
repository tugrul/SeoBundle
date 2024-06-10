<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('ModifiedType', 'https://example.com')]
class ModifiedTypeContext
{
    #[JsonLd\Property('existsField')]
    public string $f1 = 'abcd';

    #[JsonLd\Property('secondField')]
    public int $f2 = 9876;

    #[JsonLd\Property('changedField')]
    public string $f3 = 'aaaa';

    public int $nonMapped = 7654;
}
