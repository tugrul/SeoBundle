<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('abc')]
#[JsonLd\Type('def', 'https://example.com')]
#[JsonLd\Type('ghi', 'https://example.org')]
class MixedContextModel
{
    #[JsonLd\Property('fil1')]
    public string $field1 = 'aaa';

    #[JsonLd\Property('fil2')]
    public string $field2 = 'bbb';

    #[JsonLd\Property('fil3')]
    public string $field3 = 'ccc';
}
