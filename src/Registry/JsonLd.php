<?php

namespace Tug\SeoBundle\Registry;

class JsonLd implements JsonLdInterface
{
    const DEFAULT_CONTEXT_PLACEHOLDER = '#default#';

    protected ?string $defaultContext = null;

    protected array $registry = [];

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

    public function getFieldTypes(array|string $type, string $field): array
    {
        if (is_string($type)) {
            $context = $this->defaultContext;
        } else {
            switch (count($type)) {
                case 0: return [];
                case 1: $type = $type[0]; break;
                default: $context = $type[0] ?? $this->defaultContext; $type = $type[1];
            }
        }

        $context = $context ?? self::DEFAULT_CONTEXT_PLACEHOLDER;

        if (!isset($this->registry[$context])) {
            return [];
        }

        if (!isset($this->registry[$context][$type])) {
            return [];
        }

        $item = $this->registry[$context][$type];

        if (isset($item['fields'][$field])) {
            return $item['fields'][$field];
        }

        $parents = $item['parents'] ?? [];

        foreach ($parents as $parent) {
            $result = $this->getFieldTypes($parent, $field);

            if (!empty($result)) {
                return $result;
            }
        }

        return [];
    }


}