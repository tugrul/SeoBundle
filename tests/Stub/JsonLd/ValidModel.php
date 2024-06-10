<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('ValidKind', 'https://example.org')]
class ValidModel
{
    #[JsonLd\Property('@id')]
    public string $id = 'https://example.org#valid';

    #[JsonLd\Property('hidden1')]
    private string $hidden1 = 'abc';

    #[JsonLd\Property('hidden2')]
    protected string $hidden2 = 'def';

    #[JsonLd\Property('access')]
    protected string $access = 'ghi';

    #[JsonLd\Property('mapped')]
    public int $skipped = 456;

    protected int $unmapped = 123;

    #[JsonLd\Property('var', ['AnotherValidKind', 'GoodValidKind'])]
    private mixed $var = null;

    public function getAccess(): string
    {
        return $this->access;
    }

    #[JsonLd\Property('mapped')]
    public function getUnmapped(): int
    {
        return $this->unmapped;
    }

    public function getVar(): mixed
    {
        return $this->var;
    }

    public function setVar(mixed $var): static
    {
        $this->var = $var;

        return $this;
    }

}
