<?php

namespace Tug\SeoBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\{NormalizerAwareInterface, NormalizerAwareTrait, NormalizerInterface};
use Tug\SeoBundle\Registry\JsonLdInterface;
use Tug\SeoBundle\Translate\TranslatorInterface;


class JsonLdArrayNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    const REPLACE_REGEX = '/^{(\w+)}$/';
    const EXPAND_REGEX = '/^<(\w+)>$/';

    use NormalizerAwareTrait;

    protected TranslatorInterface $translator;

    protected JsonLdInterface $jsonLdRegistry;

    public function __construct(TranslatorInterface $translator, JsonLdInterface $jsonLdRegistry)
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

        if (isset($object['@type'])) {
            if (isset($object['@context'])) {
                $currentType = [$object['@context'], $object['@type']];
            } else {
                $currentType = $object['@type'];
            }
        } else {
            $currentType = [];
        }

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

                        $targetTypes = $this->jsonLdRegistry->getFieldTypes($currentType, $key);
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

                $targetTypes = $this->jsonLdRegistry->getFieldTypes($currentType, $key);
                $context = [...$context, 'jsonLd' => [...$options, 'target' => $targetTypes, 'level' => $level + 1]];
            } elseif (is_null($value)) {
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