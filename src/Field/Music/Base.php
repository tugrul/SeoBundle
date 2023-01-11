<?php

namespace Tug\SeoBundle\Field\Music;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    function getRootName(): string
    {
        return 'music';
    }
}
