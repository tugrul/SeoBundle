<?php

namespace Tug\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Reference;
use Tug\SeoBundle\JsonLd\Filter\FilterInterface;
use Tug\SeoBundle\Registry\JsonLdInterface;

class JsonLdRegistryPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(JsonLdInterface::class)) {
            return;
        }

        $definition = $container->findDefinition(JsonLdInterface::class);

        $filters = $container->findTaggedServiceIds('tug_seo.jsonld.filter');

        foreach ($filters as $id => $tags) {
            $filter = $container->findDefinition($id);
            $class = $filter->getClass();

            if (!is_subclass_of($class, FilterInterface::class)) {
                $message = sprintf('The service "%s" must implement FilterInterface.', $id);
                throw new \InvalidArgumentException($message);
            }

            $definition->addMethodCall('setFilter', [$class::getHandle(), [new Reference($id), 'action']]);
        }
    }

}