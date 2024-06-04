<?php

namespace Tug\SeoBundle\Schema\Type;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('ListItem', 'https://schema.org')]
class ListItem extends Thing
{
    #[JsonLd\Property('item')]
    protected mixed $item = null;

    public function getItem(): mixed
    {
        return $this->item;
    }

    public function setItem(mixed $item): static
    {
        $this->item = $item;

        return $this;
    }
}
