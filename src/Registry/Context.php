<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Field\FieldData;

class Context implements ContextInterface
{
    protected array $fields = [];

    protected array $defaultFields = [];

    protected array $routeFields = [];

    protected array $globalParameters = [];

    protected array $parameters = [];

    protected array $defaultParameters = [];

    protected array $routeParameters = [];

    protected array $options = [];

    protected array $defaultOptions = [];

    protected array $routeOptions = [];

    protected array $hierarchy = [];


    public function setFields(array $fields, bool $merge = true): self
    {
        $this->fields = $merge ? array_replace_recursive($this->fields, $fields) : $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function setDefaultFields(array $fields, bool $merge = true): self
    {
        $this->defaultFields = $merge ? array_replace_recursive($this->defaultFields, $fields) : $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultFields(): array
    {
        return $this->defaultFields;
    }

    public function setRouteFields(array $fields, bool $merge = true): self
    {
        $this->routeFields = $merge ? array_replace_recursive($this->routeFields, $fields) : $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteFields(): array
    {
        return $this->routeFields;
    }

    public function setParameters(array $parameters, bool $merge = true): self
    {
        $this->parameters = $merge ? array_replace_recursive($this->parameters, $parameters) : $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setGlobalParameters(array $parameters, bool $merge = true): self
    {
        $this->globalParameters = $merge ? array_replace($this->globalParameters, $parameters) : $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getGlobalParameters(): array
    {
        return $this->globalParameters;
    }

    public function setDefaultParameters(array $parameters, bool $merge = true): self
    {
        $this->defaultParameters = $merge ? array_replace_recursive($this->defaultParameters, $parameters) : $parameters;

        return $this;
    }
    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return $this->defaultParameters;
    }

    public function setRouteParameters(array $parameters, bool $merge = true): self
    {
        $this->routeParameters = $merge ? array_replace_recursive($this->routeParameters, $parameters): $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setOptions(array $options, bool $merge = true): self
    {
        $this->options = $merge ? array_replace_recursive($this->options, $options) : $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function setDefaultOptions(array $options, bool $merge = true): self
    {
        $this->defaultOptions = $merge ? array_replace_recursive($this->defaultOptions, $options) : $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultOptions(): array
    {
        return $this->defaultOptions;
    }

    public function setRouteOptions(array $options, bool $merge = true): self
    {
        $this->routeOptions = $merge ? array_replace_recursive($this->routeOptions, $options) : $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteOptions(): array
    {
        return $this->routeOptions;
    }

    public function setHierarchy(array $hierarchy): self
    {
        $this->hierarchy = $hierarchy;

        return $this;
    }

    /**
     * @return array
     */
    public function getHierarchy(): array
    {
        return $this->hierarchy;
    }

    public function getParentRouteName(string $routeName): ?string
    {
        return $this->hierarchy[$routeName] ?? null;
    }

    protected function expandNamespace(array $values, array $namespace): mixed
    {
        foreach ($namespace as $name) {
            if (!isset($values[$name])) {
                return null;
            }

            $values = $values[$name];
        }

        return $values;
    }

    public function getFinalField(string $routeName, array $namespace, bool $strict = false): mixed
    {
        if (empty($namespace)) {
            return null;
        }

        $fields = $strict ? [] : $this->getFields();
        $defaultFields = $this->getDefaultFields();
        $routeFields = $this->getRouteFields();

        if (!empty($routeName) && array_key_exists($routeName, $routeFields)) {
            $defaultFields = array_replace_recursive($defaultFields, $routeFields[$routeName]);
        } elseif ($strict) {
            return null;
        }

        $fields = array_replace_recursive($defaultFields, $fields);

        return $this->expandNamespace($fields, $namespace);
    }

    public function getFinalParameters(string $routeName, array $namespace): array
    {
        $parameters = $this->getDefaultParameters();
        $routeParameters = $this->getRouteParameters();

        if (!empty($routeName) && array_key_exists($routeName, $routeParameters)) {
            $parameters = array_replace_recursive($parameters, $routeParameters[$routeName]);
        }

        $parameters = array_replace_recursive($parameters, $this->getParameters());

        if (!empty($namespace)) {
            $parameters = $this->expandNamespace($parameters, $namespace) ?? [];
        }

        $globalParameters = $this->getGlobalParameters();

        return array_replace($globalParameters, $parameters);
    }

    public function getFinalOptions(string $routeName, array $namespace): array
    {
        if (empty($namespace)) {
            return [];
        }

        $options = $this->getDefaultOptions();
        $routeOptions = $this->getRouteOptions();

        if (!empty($routeName) && array_key_exists($routeName, $routeOptions)) {
            $options = array_replace_recursive($options, $routeOptions[$routeName]);
        }

        $options = array_replace_recursive($options, $this->getOptions());

        return $this->expandNamespace($options, $namespace) ?? [];
    }

    public function getFieldData(string $routeName, array $namespace, bool $strict = false): ?FieldData
    {
        $field = $this->getFinalField($routeName, $namespace, $strict);

        if (empty($field)) {
            return null;
        }

        $parentRouteName = $this->getParentRouteName($routeName);

        $fieldData = new FieldData();

        if (!empty($parentRouteName)) {
            $fieldData->setParent($this->getFieldData($parentRouteName, $namespace, true));
        }

        $parameters = $this->getFinalParameters($routeName, $namespace);
        $options = $this->getFinalOptions($routeName, $namespace);

        $fieldData->setContent($field);
        $fieldData->setParameters($parameters);
        $fieldData->setOptions($options);

        return $fieldData;
    }
}
