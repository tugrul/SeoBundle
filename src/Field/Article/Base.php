<?php

namespace Tug\SeoBundle\Field\Article;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    protected function getRootName(): string
    {
        return 'article';
    }
}
