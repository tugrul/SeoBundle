<?php

namespace Tug\SeoBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class RouteNameProvider implements RouteNameProviderInterface
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCurrentRouteName(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return is_null($request) ? '' : $request->get('_route', '');
    }
}
