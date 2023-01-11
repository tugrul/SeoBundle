<?php

namespace Tug\SeoBundle\Field;

class FieldData
{
    protected string|array $content;

    protected array $parameters = [];

    protected array $options = [];

    protected ?FieldData $parent = null;

    /**
     * @return array|string
     */
    public function getContent(): array|string
    {
        return $this->content;
    }

    /**
     * @param array|string $content
     * @return FieldData
     */
    public function setContent(array|string $content): FieldData
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
    public function setParameters(array $parameters): FieldData
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
    public function setOptions(array $options, bool $merge = true): FieldData
    {
        $this->options = $merge ? array_replace($this->options, $options) : $options;

        return $this;
    }

    /**
     * @return FieldData|null
     */
    public function getParent(): ?FieldData
    {
        return $this->parent;
    }

    /**
     * @param FieldData|null $parent
     * @return FieldData
     */
    public function setParent(?FieldData $parent): FieldData
    {
        $this->parent = $parent;
        return $this;
    }
}
