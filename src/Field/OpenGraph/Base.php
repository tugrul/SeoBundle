<?php

namespace Tug\SeoBundle\Field\OpenGraph;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    public function getRootName(): string
    {
        return 'og';
    }
}
