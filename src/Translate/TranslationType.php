<?php

namespace Tug\SeoBundle\Translate;

enum TranslationType: string
{
    case None = 'none';
    case Default = 'default';
    case Icu = 'icu';
}
