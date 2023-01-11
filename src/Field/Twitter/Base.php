<?php

namespace Tug\SeoBundle\Field\Twitter;

use Tug\SeoBundle\Field\MetaScope;
use Tug\SeoBundle\Model\Meta;

abstract class Base extends MetaScope
{
    function getRootName(): string
    {
        return 'twitter';
    }

    protected function setMetaHandle(Meta $meta, string $handle): void
    {
        $meta->setName($handle);
    }
}
