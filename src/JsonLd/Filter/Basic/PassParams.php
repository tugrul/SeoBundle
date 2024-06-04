<?php

namespace Tug\SeoBundle\JsonLd\Filter\Basic;

use Tug\SeoBundle\JsonLd\Filter\{FilterInterface, FilterData};

class PassParams implements FilterInterface
{
    public function action(FilterData $data): array
    {
        return $data->params;
    }

    public static function getHandle(): string
    {
        return 'pass_params';
    }
}
