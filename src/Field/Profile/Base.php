<?php

namespace Tug\SeoBundle\Field\Profile;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    function getRootName(): string
    {
        return 'profile';
    }
}
