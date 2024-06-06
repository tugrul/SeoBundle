<?php

namespace Tug\SeoBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\{NormalizerAwareInterface, NormalizerAwareTrait, NormalizerInterface};
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\Translation\TranslatableInterface;
use Tug\SeoBundle\Exception\JsonLdAttributeException;
use Tug\SeoBundle\Exception\JsonLdTypeException;
use Tug\SeoBundle\JsonLd\Reflection\{Attribute as JsonLdAttribute, Field as JsonLdField};
use Tug\SeoBundle\Registry\JsonLd as JsonLdRegistryInterface;

use Tug\SeoBundle\JsonLd\Filter\FilterData;

class JsonLdObjectNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    protected JsonLdRegistryInterface $jsonLdRegistry;
    protected PropertyAccessorInterface $propertyAccessor;

    public function __construct(JsonLdRegistryInterface $jsonLdRegistry,
                                ?PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->jsonLdRegistry = $jsonLdRegistry;

        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return array|string
     * @throws \ReflectionException
     * @throws JsonLdTypeException
     * @throws JsonLdAttributeException
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array | string
    {
        $options = $context['jsonLd'] ?? [];

        $target = $options['target'] ?? [];

        $level = $options['level'] ?? 0;

        if ($object::class === \stdClass::class) {
            $object = array_map(fn($item) => new JsonLdField($item), get_object_vars($object));

            $normalizer = fn($value) => $this->normalizer->normalize($value, $format,
                [...$context, 'jsonLd' => [...$options, 'target' => $target, 'level' => $level + 1]]);

            return $this->mapResult($object, $normalizer);
        }

        $reflector = JsonLdAttribute::getInstance($object::class);
        $origRef = $reflector->getReflector();

        if ($origRef->isEnum()) {
            return $object->value;
        }

        if ($origRef->isIterable()) {
            $result = [];

            foreach ($object as $key => $value) {
                $result[$key] = $this->normalizer->normalize($value, $format, $context);
            }

            return $result;
        }

        $reflector->setDefaultContext($options['defaultContext'] ?? null);

        $type = $reflector->getType(is_string($target) ? [$target] : $target);

        $fields = $reflector->toArray($type, $object, $level);

        $normalizer = fn($value, $types) => $this->normalizer->normalize($value, $format,
            [...$context, 'jsonLd' => [...$options, 'target' => $types, 'level' => $level + 1]]);

        $filter = fn($name, $property, $value, $params) => $this->jsonLdRegistry->applyFilter($name,
            new FilterData($name, $type, $property, $level, $value, $params));

        $result = $this->mapResult($fields, $normalizer, $filter);

        $properties = array_map(fn($property) => new JsonLdField($object, $property),
            $reflector->getGenericProperties($type, $level));

        $result = array_merge($result, $this->mapResult($properties, $normalizer, $filter));

        return $type->toArray() + $result;
    }

    /**
     * @param JsonLdField[] $fields
     * @param callable $normalizer
     * @param callable|null $filter
     * @return array
     */
    protected function mapResult(array $fields, callable $normalizer, ?callable $filter = null): array
    {
        $result = [];

        foreach ($fields as $name => $field) {

            $value = $field->value;

            if (!is_null($field->property)) {
                $name = $field->property->name;
            }

            if (is_null($value) || (is_array($value) && count($value) === 0)) {
                continue;
            }

            if (!is_null($filter)) {
                $value = $this->applyFilters($field, $filter);

                if (is_null($value)) {
                    continue;
                }
            }

            if (is_scalar($value)) {
                $result[$name] = $value;
                continue;
            }

            $result[$name] = $normalizer($value, $field->getTypes(), $name);
        }

        return $result;
    }

    protected function applyFilters(JsonLdField $field, callable $filter): mixed
    {
        $value = $field->value;

        if (is_object($value) && is_iterable($value)) {
            $values = [];

            foreach ($value as $key => $item) {
                $values[$key] = $this->applyFilters(new JsonLdField($item, $field->property), $filter);
            }

            return new \ArrayIterator($values);
        }

        $filters = $field->getFilters();

        foreach ($filters as $filterName => $params) {
            $isExpandableValue = is_object($value) || is_array($value);

            $value = $filter($filterName, $field->property, $value, $isExpandableValue
                ? $this->expandFilterParams($value, $params) : $params);

            if (is_null($value)) {
                break;
            }
        }

        return $value;
    }

    protected function expandFilterParams(object|array $value, array $params): array
    {
        $result = [];

        foreach ($params as $name => $param) {
            if (is_string($name) && str_starts_with($name, '*')) {
                $name = substr($name, 1);

                if (is_string($param)) {
                    $result[$name] = $this->propertyAccessor->getValue($value, $param);
                    continue;
                }
            }

            $result[$name] = is_array($param) ? $this->expandFilterParams($value, $param) : $param;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_object($data) && $this->isNotExcluded($data) && ($format === 'ld+json');
    }

    protected function isNotExcluded(mixed $data): bool
    {
        return !(($data instanceof \DateTimeInterface) || ($data instanceof \DateInterval) ||
            ($data instanceof TranslatableInterface));
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ 'object' => false, '*' => null ];
    }

}