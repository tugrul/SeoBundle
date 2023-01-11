<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\ModelInterface;

class DummyFaultyModel implements ModelInterface
{
    public static function getHandleName(): string
    {
        return '';
    }
}
