<?php

namespace Tug\SeoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Tug\SeoBundle\Registry\ContextInterface;
use Tug\SeoBundle\Registry\ContentInterface;
use Tug\SeoBundle\Registry\JsonLdInterface;
use Tug\SeoBundle\Translate\TranslatorInterface;
use Exception;

class TugSeoExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        if ($container->has(ContextInterface::class)) {
            $context = $container->findDefinition(ContextInterface::class);

            $context->addMethodCall('setDefaultFields', [ $config['default'] ]);
            $context->addMethodCall('setRouteFields', [ $config['routes'] ]);

            $context->addMethodCall('setGlobalParameters', [ $config['parameters']['global'] ]);
            $context->addMethodCall('setDefaultParameters', [ $config['parameters']['default'] ]);
            $context->addMethodCall('setRouteParameters', [ $config['parameters']['routes'] ]);

            $context->addMethodCall('setDefaultOptions', [ $config['options']['default'] ]);
            $context->addMethodCall('setRouteOptions', [ $config['options']['routes'] ]);

            $context->addMethodCall('setHierarchy', [ $this->normalizeRouteHierarchy($config['hierarchy']) ]);
        }

        if ($container->has(ContentInterface::class)) {
            $content = $container->findDefinition(ContentInterface::class);

            $content->addMethodCall('setContents', [ $config['contents'] ]);
        }

        if ($container->has(TranslatorInterface::class)) {

            $translation = $config['translation'];

            $translator = $container->findDefinition(TranslatorInterface::class);
            $translator->addMethodCall('setType', [ $translation['type'] ]);
            $translator->addMethodCall('setDomain', [ $translation['domain'] ]);
            $translator->addMethodCall('setFormatTemplate', [ $translation['format_template'] ]);
        }

        if ($container->has(JsonLdInterface::class)) {
            $jsonLd = $container->findDefinition(JsonLdInterface::class);

            $jsonLdConfig = $config['jsonLd'];

            $jsonLd->addMethodCall('setDefaultContext', [ $jsonLdConfig['context'] ]);

            $jsonLd->addMethodCall('setTypes', [ $jsonLdConfig['types'] ]);
        }
    }

    protected function normalizeRouteHierarchy(array $hierarchy): array
    {
        $normalized = [];

        foreach($hierarchy as $parent => $routes) {
            foreach ($routes as $route) {
                $normalized[$route] = $parent;
            }
        }

        return $normalized;
    }
}
