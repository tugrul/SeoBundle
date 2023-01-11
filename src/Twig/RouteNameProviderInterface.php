<?php

namespace Tug\SeoBundle\Twig;

interface RouteNameProviderInterface
{
    public function getCurrentRouteName() : string;
}
