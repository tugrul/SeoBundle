<?php

namespace Tug\SeoBundle\Provider;

interface ContentIndexProviderInterface
{
    public function setSeed(int $seed);

    public function getIndex(int $range): int;
}
