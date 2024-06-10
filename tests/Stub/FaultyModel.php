<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\ModelInterface;

class FaultyModel implements ModelInterface
{
    public static function getHandleName(): string
    {
        return '';
    }
}
