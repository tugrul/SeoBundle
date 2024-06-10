<?php

namespace Tug\SeoBundle\JsonLd\Modifier;

interface ModifierInterface
{
    public function modify(ModifierData $data): iterable;

    public static function getContext(): ?string;

    public static function getType(): string;
}
