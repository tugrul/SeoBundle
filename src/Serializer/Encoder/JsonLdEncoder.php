<?php

namespace Tug\SeoBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

class JsonLdEncoder implements EncoderInterface
{
    public function encode(mixed $data, string $format, array $context = []): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function supportsEncoding(string $format): bool
    {
        return 'ld+json' === $format;
    }
}