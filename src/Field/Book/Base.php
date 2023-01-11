<?php

namespace Tug\SeoBundle\Field\Book;

use Tug\SeoBundle\Field\MetaScope;

abstract class Base extends MetaScope
{
    function getRootName(): string
    {
        return 'book';
    }
}
