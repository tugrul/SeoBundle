<?php

namespace Tug\SeoBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\{NormalizerAwareInterface, NormalizerAwareTrait, NormalizerInterface};
use Tug\SeoBundle\JsonLd\Modifier\ModifierData;
use Tug\SeoBundle\Registry\JsonLdInterface as JsonLdRegistryInterface;
use Tug\SeoBundle\Translate\TranslatorInterface;
use Tug\SeoBundle\JsonLd\Attribute\Type as JsonLdType;


class JsonLdArrayNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    const REPLACE_REGEX = '/^{(\w+)}$/';
    const EXPAND_REGEX = '/^<(\w+)>$/';

    use NormalizerAwareTrait;

    protected TranslatorInterface $translator;

    protected JsonLdRegistryInterface $jsonLdRegistry;

    public function __construct(TranslatorInterface $translator, JsonLdRegistryInterface $jsonLdRegistry)
    {
        $this->translator = $translator;

        $this->jsonLdRegistry = $jsonLdRegistry;
    }

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $options = $context['jsonLd'] ?? [];

        $parameters = $options['parameters'] ?? [];

        $level = $options['level'] ?? 0;

        $result = [];

        $currentType = isset($object['@type']) ? new JsonLdType($object['@type'], $object['@context'] ?? null) : null;

        foreach ($object as $key => $value) {

            if (is_string($key)) {
                if (str_starts_with($key, '$')) {
                    $key = substr($key, 1);
                    if (is_string($value)) {
                        $result[$key] = $this->translator->translate($value, $parameters);
                        continue;
                    }
                } else {
                    $match = $this->matchField($key, $parameters);

                    if (null !== $match) {
                        list($expand, $target) = $match;

                        if (is_null($target)) {
                            continue;
                        }

                        if (is_scalar($target)) {
                            $result[$key] = $target;
                            continue;
                        }

                        $targetTypes = is_null($currentType) ? [] : $this->jsonLdRegistry->getFieldTypes($currentType, $key);
                        $context = [...$context, 'jsonLd' => [...$options, 'target' => $targetTypes,
                            'level' => $level + 1]];

                        $target = $this->normalizer->normalize($target, $format, $context);

                        if ($expand) {
                            $value = is_null($value) ? [] : (is_array($value) ? $value : [$value]);
                            $result = array_merge($result, $value, $target);
                        } else {
                            $result[$key] = $target;
                        }

                        continue;
                    }
                }

                $targetTypes = is_null($currentType) ? [] : $this->jsonLdRegistry->getFieldTypes($currentType, $key);
                $context = [...$context, 'jsonLd' => [...$options, 'target' => $targetTypes, 'level' => $level + 1]];
            }

            if (is_null($value)) {
                continue;
            }

            if (is_string($value) && null !== ($match = $this->matchField($value, $parameters))) {
                list($expand, $value) = $match;

                if (is_null($value)) {
                    continue;
                }

                if (is_string($value) && $expand) {
                    $result[$key] = $this->translator->translate($value, $parameters);
                    continue;
                }

                if (is_scalar($value)) {
                    $result[$key] = $value;
                    continue;
                }

                $value = $this->normalizer->normalize($value, $format, $context);

                if ($expand) {
                    $result = array_merge($result, $value);
                } else {
                    $result[$key] = $value;
                }

                continue;
            }

            if (is_scalar($value)) {
                $result[$key] = $value;
                continue;
            }

            $result[$key] = $this->normalizer->normalize($value, $format, $context);
        }

        $nonIntKeys = array_filter(array_keys($result), fn($item) => !is_int($item));

        if (count($nonIntKeys) === 0) {
            return array_values($result);
        }

        if ($currentType !== null) {
            $modifierData = new ModifierData($currentType, $level, $result);
            $modifier = $this->jsonLdRegistry->applyModifier($modifierData);

            foreach ($modifier as $key => $value) {
                if (is_null($value) || (is_array($value) && count($value) === 0)) {
                    unset($result[$key]);
                    continue;
                }

                if (is_scalar($value)) {
                    $result[$key] = $value;
                    continue;
                }

                $result[$key] = $this->normalizer->normalize($value, $format, $context);
            }
        }

        return $result;
    }

    protected function matchField(string $value, array $parameters): ?array
    {
        if (preg_match(self::REPLACE_REGEX, $value, $matches) > 0
            && array_key_exists($matches[1], $parameters)) {
            return [false, $parameters[$matches[1]]];
        }

        if (preg_match(self::EXPAND_REGEX, $value, $matches) > 0
            && array_key_exists($matches[1], $parameters)) {
            return [true, $parameters[$matches[1]]];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_array($data) && ($format === 'ld+json');
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ 'object' => null, '*' => false ];
    }
}