<?php

namespace Tug\SeoBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\{NormalizerAwareInterface, NormalizerAwareTrait, NormalizerInterface};
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Tug\SeoBundle\Exception\JsonLdAttributeException;
use Tug\SeoBundle\Exception\JsonLdTypeException;
use Tug\SeoBundle\Reflection\JsonLd\Attribute as JsonLdAttribute;

class JsonLdObjectNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

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
            return $this->mapResult(get_object_vars($object),
                fn($field) => $this->normalizer->normalize($field, $format,
                    [...$context, 'jsonLd' => [...$options, 'target' => $target, 'level' => $level + 1]]));
        }

        $reflector = JsonLdAttribute::getInstance($object::class);

        if ($reflector->getReflector()->isEnum()) {
            return $object->value;
        }

        $reflector->setDefaultContext($options['defaultContext'] ?? null);

        $type = $reflector->getType(is_string($target) ? [$target] : $target);

        $fields = $reflector->toArray($type, $object, $level);

        return $type->toArray() + $this->mapResult($fields,
                fn($field) => $this->normalizer->normalize($field->value, $format,
                    [...$context, 'jsonLd' => [...$options, 'target' => $field->types,
                        'level' => $level + 1]]), fn($field) => $field->value);
    }

    protected function mapResult(array $fields, callable $normalizer, ?callable $extractor = null): array
    {
        $result = [];

        $extractor = $extractor ?? fn($value) => $value;

        foreach ($fields as $name => $field) {
            $value = $extractor($field);

            if (is_scalar($value)) {
                $result[$name] = $value;
                continue;
            }

            if (is_null($value) || (is_array($value) && count($value) === 0)) {
                continue;
            }

            $result[$name] = $normalizer($field, $name);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_object($data) && ($format === 'ld+json');
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ 'object' => false, '*' => null ];
    }

}