<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Provider\RouteNameProviderInterface;

class RouteNameProvider implements RouteNameProviderInterface
{
    protected string $currentRouteName = '';

    public function getCurrentRouteName(): string
    {
        return $this->currentRouteName;
    }

    public function setCurrentRouteName(string $currentRouteName): void
    {
        $this->currentRouteName = $currentRouteName;
    }
}
