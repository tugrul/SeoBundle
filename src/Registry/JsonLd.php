<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\JsonLd\Filter\FilterData;
use Tug\SeoBundle\JsonLd\Modifier\{ModifierData, ModifierInterface};
use Tug\SeoBundle\JsonLd\Attribute\Type as JsonLdType;

class JsonLd implements JsonLdInterface
{
    const DEFAULT_CONTEXT_PLACEHOLDER = '#default#';

    protected ?string $defaultContext = null;

    protected array $registry = [];

    protected array $filters = [];

    protected array $modifiers = [];

    public function setTypes(array $types): static
    {
        foreach ($types as $type) {
            $context = $type['context'] ?? $this->defaultContext ?? self::DEFAULT_CONTEXT_PLACEHOLDER;
            if (!isset($this->registry[$context])) {
                $this->registry[$context] = [];
            }

            $this->registry[$context][$type['name']] = [
                'parents' => $type['parents'] ?? [],
                'fields' => $type['fields']
            ];
        }

        return $this;
    }

    public function setDefaultContext(?string $context): static
    {
        $this->defaultContext = $context;

        return $this;
    }

    public function getFieldTypes(JsonLdType $type, string $field): array
    {
        $context = $type->context ?? $this->defaultContext ?? self::DEFAULT_CONTEXT_PLACEHOLDER;
        $typeName = $type->name;

        if (!isset($this->registry[$context])) {
            return [];
        }

        if (!isset($this->registry[$context][$typeName])) {
            return [];
        }

        $item = $this->registry[$context][$typeName];

        if (isset($item['fields'][$field])) {
            return $item['fields'][$field];
        }

        $parents = $item['parents'] ?? [];

        foreach ($parents as $parent) {
            $result = $this->getFieldTypes(JsonLdType::from($parent), $field);

            if (!empty($result)) {
                return $result;
            }
        }

        return [];
    }

    public function setFilter(string $handle, callable $filter): static
    {
        $this->filters[$handle] = $filter;

        return $this;
    }

    public function applyFilter(string $handle, FilterData $data): mixed
    {
        if (!isset($this->filters[$handle])) {
            $message = sprintf('Filter %s is not defined in registry', $handle);
            throw new \RuntimeException($message);
        }

        return $this->filters[$handle]($data);
    }

    public function setModifier(ModifierInterface $modifier): static
    {
        $context = $modifier::getContext() ?? $this->defaultContext ?? self::DEFAULT_CONTEXT_PLACEHOLDER;

        $type = $modifier::getType();

        if (isset($this->modifiers[$context])) {
            $this->modifiers[$context][$type] = $modifier;
        } else {
            $this->modifiers[$context] = [$type => $modifier];
        }

        return $this;
    }

    public function applyModifier(ModifierData $data): iterable
    {
        $context = $data->type->context ?? $this->defaultContext ?? self::DEFAULT_CONTEXT_PLACEHOLDER;
        $name = $data->type->name;

        if (!isset($this->modifiers[$context]) || !isset($this->modifiers[$context][$name])) {
            return [];
        }

        return $this->modifiers[$context][$name]->modify($data);
    }

}

