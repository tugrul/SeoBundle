<?php

namespace Tug\SeoBundle\Twig;

use Tug\SeoBundle\Registry\{ContextInterface, ContentInterface, FieldInterface, RendererInterface};
use Tug\SeoBundle\Provider\ContentIndexProviderInterface;
use Tug\SeoBundle\Translate\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\{TwigFunction, TwigFilter};

use Tug\SeoBundle\Provider\RouteNameProviderInterface;

class TugSeoExtension extends AbstractExtension
{
    protected FieldInterface $field;

    protected ContextInterface $context;

    protected ContentInterface $content;

    protected RendererInterface $renderer;

    protected RouteNameProviderInterface $routeNameProvider;

    protected TranslatorInterface $translator;

    protected ContentIndexProviderInterface $contentIndexProvider;

    public function __construct(FieldInterface $field, ContextInterface $context, ContentInterface $content,
                                RendererInterface $renderer, RouteNameProviderInterface $routeNameProvider,
                                TranslatorInterface $translator, ContentIndexProviderInterface $contentIndexProvider)
    {
        $this->field = $field;

        $this->context = $context;

        $this->content = $content;

        $this->renderer = $renderer;

        $this->routeNameProvider = $routeNameProvider;

        $this->translator = $translator;

        $this->contentIndexProvider = $contentIndexProvider;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tug_seo', [$this, 'getRenderedFields'], ['is_safe' => ['html']]),
            new TwigFunction('tug_seo_is_route_active', [$this, 'isRouteActive'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('tug_seo_content', [$this, 'getContent'], ['is_safe' => ['html']])
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

    public function getContent(string $blockName, int $seed, array $params = []): string
    {
        $routeName = $this->routeNameProvider->getCurrentRouteName();

        if (empty($routeName) || str_starts_with($routeName, '_')) {
            return '';
        }

        $content = $this->content->getContent($blockName);

        if (empty($content)) {
            return '';
        }

        $params = array_merge($this->context->getFinalParameters($routeName, []),
            array_filter($params, fn($item) => is_scalar($item)));

        $this->contentIndexProvider->setSeed($seed);

        $content = $this->composeContent($content, $params);

        $this->contentIndexProvider->reset();

        return $content;
    }

    protected function composeContent($content, $params): string {
        $result = [];

        foreach ($content as $item) {
            if (is_string($item)) {
                $result[] = $this->translator->translate($content, $params);
            } elseif (is_array($item)) {
                $item = array_values($item);
                $index = $this->contentIndexProvider->getIndex(count($item) - 1);
                $item = $item[$index];

                if (is_string($item)) {
                    $result[] = $this->translator->translate($item, $params);
                } elseif (is_array($item)) {
                    $result[] = $this->composeContent($item, $params);
                }
            }
        }

        return implode(' ', $result);
    }
}

