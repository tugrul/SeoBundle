<?php

namespace Tug\SeoBundle\Schema\Enumeration;

enum ItemListOrderType: string
{
    case Ascending = 'https://schema.org/ItemListOrderAscending';

    case Descending = 'https://schema.org/ItemListOrderDescending';

    case Unordered = 'https://schema.org/ItemListOrderUnordered';
}
