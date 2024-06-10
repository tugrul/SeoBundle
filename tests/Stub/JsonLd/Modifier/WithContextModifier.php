<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd\Modifier;

use Tug\SeoBundle\JsonLd\Modifier\{ModifierData, ModifierInterface};

class WithContextModifier implements ModifierInterface
{
    public function modify(ModifierData $data): iterable
    {
        yield 'existsField' => null;

        yield 'noExistsField' => 1234;

        yield 'changedField' => 'bbbb';

        yield 'nonMappedProp' => $data->object?->nonMapped ?? 7744;
    }

    public static function getContext(): ?string
    {
        return 'https://example.com';
    }

    public static function getType(): string
    {
        return 'ModifiedType';
    }

}