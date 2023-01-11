<?php

namespace Tug\SeoBundle\Model;

class Meta implements ModelInterface
{
    protected ?string $name = null;

    protected ?string $property = null;

    protected ?string $content = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Meta
     */
    public function setName(?string $name): Meta
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProperty(): ?string
    {
        return $this->property;
    }

    /**
     * @param string|null $property
     * @return Meta
     */
    public function setProperty(?string $property): Meta
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param null|string $content
     * @return Meta
     */
    public function setContent(?string $content): Meta
    {
        $this->content = $content;
        return $this;
    }

    public static function getHandleName(): string
    {
        return 'meta';
    }
}
