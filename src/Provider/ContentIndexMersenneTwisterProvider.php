<?php

namespace Tug\SeoBundle\Provider;

class ContentIndexMersenneTwisterProvider implements ContentIndexProviderInterface
{
    public function setSeed(int $seed): void
    {
        mt_srand($seed);
    }

    public function getIndex(int $range): int
    {
        return mt_rand(0, $range);
    }

    public function reset(): void
    {
        mt_srand();
    }
}
