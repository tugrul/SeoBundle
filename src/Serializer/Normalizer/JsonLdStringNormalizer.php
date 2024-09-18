<?php

namespace Tug\SeoBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonLdStringNormalizer implements NormalizerInterface
{
    const REPLACE_REGEX = '/{(\w+)}/';

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        $options = $context['jsonLd'] ?? [];

        $parameters = $options['parameters'] ?? [];

        return preg_replace_callback(self::REPLACE_REGEX,
            fn($matches) => isset($parameters[$matches[1]]) && is_string($parameters[$matches[1]])
                ? $parameters[$matches[1]] : $matches[0], $object);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return is_string($data) && ($format === 'ld+json');
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ '*' => null, 'native-string' => false  ];
    }
}
