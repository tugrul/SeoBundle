<?php

namespace Tug\SeoBundle\Provider;

interface RouteNameProviderInterface
{
    public function getCurrentRouteName() : string;
}
