<?php

namespace Tug\SeoBundle\JsonLd\Filter;

interface FilterInterface
{
    public static function getHandle(): string;

    public function action(FilterData $data): mixed;
}
