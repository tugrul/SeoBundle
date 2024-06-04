<?php

namespace Tug\SeoBundle\JsonLd\Filter\Basic;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Tug\SeoBundle\JsonLd\Filter\{FilterInterface, FilterData};

class UrlGenerator implements FilterInterface
{
    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function action(FilterData $data): ?string
    {
        if (!isset($data->params['name']) || !isset($data->params['params'])) {
            return null;
        }

        $name = $data->params['name'];
        $params = $data->params['params'];
        $type = $data->params['type'] ?? UrlGeneratorInterface::ABSOLUTE_URL;

        return (is_string($name) && is_array($params)) ? $this->urlGenerator->generate($name, $params, $type) : null;
    }

    public static function getHandle(): string
    {
        return 'generate_url';
    }
}
