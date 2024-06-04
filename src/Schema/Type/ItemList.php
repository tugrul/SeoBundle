<?php

namespace Tug\SeoBundle\Schema\Type;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;
use Tug\SeoBundle\Schema\Enumeration\ItemListOrderType;

#[JsonLd\Type('ItemList', 'https://schema.org')]
class ItemList extends Thing
{
    #[JsonLd\Property('itemListElement', ['ListItem'])]
    protected array $items = [];

    #[JsonLd\Property('itemListOrder')]
    protected ?ItemListOrderType $itemListOrder = null;

    #[JsonLd\Property('numberOfItems')]
    public function getNumberOfItems(): int
    {
        return count($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function addItem(mixed $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItemListOrder(): ?ItemListOrderType
    {
        return $this->itemListOrder;
    }

    public function setItemListOrder(?ItemListOrderType $itemListOrder): ItemList
    {
        $this->itemListOrder = $itemListOrder;
        return $this;
    }


}
