<?php

namespace Tug\SeoBundle\Field;

class FieldData
{
    protected mixed $content;

    protected array $parameters = [];

    protected array $options = [];

    protected ?FieldData $parent = null;

    /**
     * @return mixed
     */
    public function getContent(): mixed
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return FieldData
     */
    public function setContent(mixed $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return FieldData
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @param bool $merge
     * @return FieldData
     */
    public function setOptions(array $options, bool $merge = true): static
    {
        $this->options = $merge ? array_replace($this->options, $options) : $options;

        return $this;
    }

    /**
     * @return FieldData|null
     */
    public function getParent(): ?static
    {
        return $this->parent;
    }

    /**
     * @param FieldData|null $parent
     * @return FieldData
     */
    public function setParent(?FieldData $parent): static
    {
        $this->parent = $parent;

        return $this;
    }
}
