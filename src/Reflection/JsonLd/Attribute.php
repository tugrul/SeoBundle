<?php

namespace Tug\SeoBundle\Reflection\JsonLd;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Tug\SeoBundle\Attribute\JsonLd;
use Tug\SeoBundle\Exception\{JsonLdTypeException, JsonLdAttributeException};

class Attribute
{
    protected static array $instances = [];

    protected ReflectionClass $reflector;

    protected ?string $defaultContext = null;

    protected ?array $types = null;

    protected ?bool $isSingleType = null;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $name)
    {
        $this->reflector = new ReflectionClass($name);
    }

    /**
     * @throws ReflectionException
     */
    public static function getInstance(string $name): static
    {
        if (isset(static::$instances[$name])) {
            return static::$instances[$name];
        }

        return static::$instances[$name] = new static($name);
    }

    public function getDefaultContext(): ?string
    {
        return $this->defaultContext;
    }

    public function setDefaultContext(?string $defaultContext): static
    {
        $this->defaultContext = $defaultContext;

        return $this;
    }

    /**
     * @return JsonLd\Type[]
     */
    public function getTypes(): array
    {
        if (is_array($this->types)) {
            return $this->types;
        }

        $attributes = $this->reflector->getAttributes(JsonLd\Type::class);

        return $this->types = array_map(fn($attribute) => $attribute->newInstance(), $attributes);
    }

    /**
     * @return bool
     */
    public function isSingleType(): bool
    {
        if (is_bool($this->isSingleType)) {
            return $this->isSingleType;
        }

        $types = $this->getTypes();

        return $this->isSingleType = count($types) === 1;
    }

    public function filterTypes(array $types, array $target): array
    {
        $result = [];

        foreach ($types as $type) {
            foreach ($target as $item) {
                if (is_array($item)) {
                    switch (count($item)) {
                        case 0: continue 2;
                        case 1: $context = $this->defaultContext ?? $type->context; $value = $item[0]; break;
                        default: $context = $item[0]; $value = $item[1];
                    }
                } else {
                    $value = $item;
                    $context = $this->defaultContext ?? $type->context;
                }

                if (($value === $type->name) && ($context === ($type->context ?? $this->defaultContext))) {
                    $result[] = $type;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $target
     * @return JsonLd\Type
     * @throws JsonLdTypeException
     */
    public function getType(array $target = []): JsonLd\Type
    {
        $types = $this->getTypes();

        $count = count($types);

        if ($count === 0) {
            throw new JsonLdTypeException('Unable to determine a suitable JsonLd\\Type attribute.');
        }

        if (count($target) === 0) {
            if ($count === 1) {
                return $types[0];
            }

            throw new JsonLdTypeException('Multiple JsonLd\\Type annotations detected,' .
                ' but no inference could be made based on the target object.');
        }

        $types = $this->filterTypes($types, $target);

        $count = count($types);

        if ($count === 0) {
            throw new JsonLdTypeException('The JsonLd\\Type annotation list does not match the target list.');
        }

        if ($count > 1) {
            throw new JsonLdTypeException('Multiple JsonLd\\Type annotations match the target list.');
        }

        return $types[0];
    }

    /**
     * @param JsonLd\Type $type
     * @param ReflectionAttribute[] $attributes
     * @param bool $includeOrphan
     * @return JsonLd\Property[]
     */
    public function filterAttributesByType(JsonLd\Type $type, array $attributes, bool $includeOrphan = true): array
    {
        /**
         * @var $instance JsonLd\Property
         */

        $result = [];

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();

            if (empty($instance->owners)) {

                if ($includeOrphan || $this->isSingleType()) {
                    $result[] = $instance;
                }

                continue;
            }

            foreach ($instance->owners as $owner) {
                if (is_string($owner) && ($type->context === $this->defaultContext)
                    && ($type->name === $owner)) {
                    $result[] = $instance;
                    continue 2;
                }

                if (is_array($owner) && (($type->context ?? $this->defaultContext) === $owner[0])
                    && ($type->name === $owner[1])) {
                    $result[] = $instance;
                    continue 2;
                }
            }
        }

        return $result;
    }

    /**
     * @param JsonLd\Type $type
     * @param array $properties
     * @param bool $includeOrphan
     * @return array
     * @throws JsonLdAttributeException
     */
    public function getTags(JsonLd\Type $type, array $properties, bool $includeOrphan = true): array
    {
        $result = [];

        foreach ($properties as $property) {
            $attributes = $this->filterAttributesByType($type, $property->getAttributes(JsonLd\Property::class),
                $includeOrphan);

            $count = count($attributes);

            if ($count === 0) {
                continue;
            }

            if ($count > 1) {
                throw new JsonLdAttributeException('Multiple JSON-LD attributes belong to the same type.');
            }

            $result[$attributes[0]->name] = new Tag($property, $attributes[0]);
        }

        return $result;
    }

    public function getGetterMethods(array $methods): array
    {
        $result = [];

        foreach ($methods as $method) {
            if ($method->isInternal() || $method->isAbstract() || $method->isConstructor()
                || $method->isDestructor() || !$method->isPublic()) {
                continue;
            }

            $name = $method->getName();

            if (str_starts_with($name, 'get')) {
                $fieldName = substr($name, 3);
            } elseif (str_starts_with($name, 'is')) {
                $fieldName = substr($name, 2);
            } else {
                continue;
            }

            $fieldName = lcfirst($fieldName);
            $result[$fieldName] = $method;
        }

        return $result;
    }

    /**
     * @return ReflectionClass
     */
    public function getReflector(): ReflectionClass
    {
        return $this->reflector;
    }

    /**
     * @param JsonLd\Type $type
     * @param object $object
     * @param int $level
     * @param bool $includeOrphan
     * @return Field[]
     * @throws JsonLdAttributeException
     * @throws ReflectionException
     */
    public function toArray(JsonLd\Type $type, object $object, int $level = 0, bool $includeOrphan = true): array
    {
        $methods = $this->reflector->getMethods();

        $getters = $this->getGetterMethods($methods);

        $taggedMethods = $this->getTags($type, $methods, $includeOrphan);

        $taggedProperties = $this->getTags($type, $this->reflector->getProperties(), $includeOrphan);

        $items = [];

        /**
         * @var $item Tag
         */
        foreach ($taggedMethods as $fieldName => $item) {
            $method = $item->field;

            if ($this->isLevelSkipable($item->property->level, $level)) {
                continue;
            }

            if ($method->isInternal() || $method->isAbstract() || $method->isConstructor()
                || $method->isDestructor() || !$method->isPublic()) {
                continue;
            }

            $result = $method->invoke($object);

            $items[$fieldName] = new Field($method->isGenerator() ? [...$result] : $result,
                $item->property->types);
        }

        /**
         * @var $property ReflectionProperty
         * @var $getter ReflectionMethod
         */
        foreach ($taggedProperties as $fieldName => $item) {
            $property = $item->field;

            if ($this->isLevelSkipable($item->property->level, $level)) {
                continue;
            }

            if (isset($items[$fieldName])) {
                continue;
            }

            if ($property->isPublic()) {
                $items[$fieldName] = new Field($property->getValue($object), $item->property->types);
                continue;
            }

            if (!isset($getters[$property->name])) {
                continue;
            }

            $getter = $getters[$property->name];

            $items[$fieldName] = new Field($getter->invoke($object), $item->property->types);
        }

        return $items;
    }

    protected function isLevelSkipable(int|array|null $targetLevel, int $currentLevel): bool
    {
        if (is_null($targetLevel)) {
            return false;
        }

        if (is_int($targetLevel)) {
            return ($targetLevel < $currentLevel);
        }

        return match (count($targetLevel)) {
            1 => $targetLevel[0] > $currentLevel,
            2 => ($targetLevel[0] > $currentLevel) || ($targetLevel[1] < $currentLevel),
            default => false,
        };
    }

}