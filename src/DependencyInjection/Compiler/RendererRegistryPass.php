<?php

namespace Tug\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Tug\SeoBundle\Registry\RendererInterface;

class RendererRegistryPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(RendererInterface::class)) {
            return;
        }

        $definition = $container->findDefinition(RendererInterface::class);
        $services = $container->findTaggedServiceIds('tug_seo.renderer');

        foreach ($services as $id => $tags) {
            $definition->addMethodCall('set', [new Reference($id)]);
        }
    }
}
