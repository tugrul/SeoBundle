<?php

namespace Tug\SeoBundle\JsonLd\Filter\Basic;

use Tug\SeoBundle\JsonLd\Filter\{FilterInterface, FilterData};

class StringValue implements FilterInterface
{
    public function action(FilterData $data): ?string
    {
        $value = $data->value;

        return is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))
            ? strval($value) : null;
    }

    public static function getHandle(): string
    {
        return 'strval';
    }
}
