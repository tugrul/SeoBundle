<?php

namespace Tug\SeoBundle\Schema\Type;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('BreadcrumbList', 'https://schema.org')]
class BreadcrumbList extends ItemList
{
}
