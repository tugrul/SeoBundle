<?php

namespace Tug\SeoBundle\Model;

class Link implements ModelInterface
{
    protected string $rel;

    protected string $href;

    protected ?string $type = null;

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @param string $rel
     * @return Link
     */
    public function setRel(string $rel): Link
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     * @return Link
     */
    public function setHref(string $href): Link
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Link
     */
    public function setType(?string $type): Link
    {
        $this->type = $type;
        return $this;
    }

    public static function getHandleName(): string
    {
        return 'link';
    }
}
