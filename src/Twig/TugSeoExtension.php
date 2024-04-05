<?php

namespace Tug\SeoBundle\Twig;

use Tug\SeoBundle\Registry\{ContextInterface, FieldInterface, RendererInterface};
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TugSeoExtension extends AbstractExtension
{
    protected FieldInterface $field;

    protected ContextInterface $context;

    protected RendererInterface $renderer;

    protected RouteNameProviderInterface $routeNameProvider;

    public function __construct(FieldInterface $field, ContextInterface $context,
                                RendererInterface $renderer, RouteNameProviderInterface $routeNameProvider)
    {
        $this->field = $field;

        $this->context = $context;

        $this->renderer = $renderer;

        $this->routeNameProvider = $routeNameProvider;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tug_seo', [$this, 'getRenderedFields'], ['is_safe' => ['html']]),
            new TwigFunction('tug_seo_is_route_active', [$this, 'isRouteActive'])
        ];
    }

    public function getRenderedFields(?int $tabSize = 4): string
    {
        $result = [];

        $routeName = $this->routeNameProvider->getCurrentRouteName();

        if (empty($routeName) || str_starts_with($routeName, '_')) {
            return '';
        }

        $fields = $this->field->getAll();

        foreach ($fields as $field) {
            $fieldData = $this->context->getFieldData($routeName, $field->getNamespace());

            if (is_null($fieldData)) {
                continue;
            }

            $models = $field->buildModels($fieldData);

            foreach ($models as $model) {
                $result[] = $this->renderer->render($model);
            }
        }

        $separator = is_null($tabSize) ? '' : PHP_EOL. str_repeat(' ', $tabSize);

        return implode($separator, $result);
    }

    public function isRouteActive(string $routeName, bool $skipRoot = true): bool
    {
        $currentRouteName = $this->routeNameProvider->getCurrentRouteName();

        if ($currentRouteName === $routeName) {
            return true;
        }

        while ($parentRouteName = $this->context->getParentRouteName($currentRouteName)) {
            if ($currentRouteName === $routeName) {
                break;
            }

            $currentRouteName = $parentRouteName;
        }

        if ($currentRouteName === $routeName && (!$skipRoot || !is_null($parentRouteName))) {
            return true;
        }

        return false;
    }
}
