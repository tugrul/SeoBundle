<?php

namespace Tug\SeoBundle\Schema\Type;

use Tug\SeoBundle\Attribute\JsonLd;

#[JsonLd\Type('BreadcrumbList', 'https://schema.org')]
class BreadcrumbList extends ItemList
{
}
