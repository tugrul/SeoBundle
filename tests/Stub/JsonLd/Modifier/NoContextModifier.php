<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd\Modifier;


use Tug\SeoBundle\JsonLd\Modifier\{ModifierData, ModifierInterface};

class NoContextModifier implements ModifierInterface
{
    public function modify(ModifierData $data): iterable
    {
        yield 'existsField2' => null;

        yield 'noExistsField2' => 5678;

        yield 'changedField2' => 'cccc';

        yield 'nonMappedProp' => $data->object?->nonMapped2 ?? 9954;
    }

    public static function getContext(): ?string
    {
        return null;
    }

    public static function getType(): string
    {
        return 'ModifiedType';
    }

}