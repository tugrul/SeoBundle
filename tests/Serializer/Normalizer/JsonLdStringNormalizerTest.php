<?php

namespace Tug\SeoBundle\Tests\Serializer\Normalizer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Tug\SeoBundle\Serializer\Normalizer\JsonLdStringNormalizer;

class JsonLdStringNormalizerTest extends TestCase
{
    protected Serializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new Serializer([
            new JsonLdStringNormalizer()
        ]);
    }

    public function testBasic(): void
    {
        $data = [
            'name' => 'Foo Bar',
            'profileUrl' => '{baseUrl}/foo-bar',
            'singleVar' => '{firstName}',
            'fullName' => '{firstName} Middle {lastName}',
            'nonExistVar' => 'Bum {nonExist} Kum'
        ];

        $target = [
            'name' => 'Foo Bar',
            'profileUrl' => 'https://example.com/foo-bar',
            'singleVar' => 'Baa',
            'fullName' => 'Baa Middle Buu',
            'nonExistVar' => 'Bum {nonExist} Kum'
        ];

        $this->assertEquals($target, $this->serializer->normalize($data, 'ld+json', [
            'jsonLd' => [
                'parameters' => [
                    'baseUrl' => 'https://example.com',
                    'firstName' => 'Baa',
                    'lastName' => 'Buu',
                    'unused' => 'Bla'
                ]
            ]
        ]));
    }
}

