<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    function getRootName(): string
    {
        return 'video';
    }
}
