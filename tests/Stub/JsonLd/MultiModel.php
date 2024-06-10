<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('abc')]
#[JsonLd\Type('def')]
class MultiModel
{
    #[JsonLd\Property('aaa')]
    public string $field0;

    #[JsonLd\Property('bbb', owners: 'abc')]
    #[JsonLd\Property('ccc', owners: 'def')]
    public string $field1;

    public string $field2;

    #[JsonLd\Property('ddd', owners: 'def')]
    public string $field3;

    #[JsonLd\Property('eee')]
    public string $field4;

    protected function getProtectedSomething(): string
    {
        return 'protected something';
    }

    protected function isProtectedSame(): bool
    {
        return true;
    }

    public function getPublicSomething(): string
    {
        return 'public something';
    }

    public function isPublicSame(): bool
    {
        return true;
    }
}