<?php

namespace Tug\SeoBundle\Model;

class Title implements ModelInterface
{
    protected ?string $value = null;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     * @return Title
     */
    public function setValue(?string $value): Title
    {
        $this->value = $value;
        return $this;
    }

    public static function getHandleName(): string
    {
        return 'title';
    }
}
