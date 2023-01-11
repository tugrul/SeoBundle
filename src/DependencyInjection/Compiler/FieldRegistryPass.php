<?php

namespace Tug\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Tug\SeoBundle\Registry\FieldInterface;

class FieldRegistryPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(FieldInterface::class)) {
            return;
        }

        $definition = $container->findDefinition(FieldInterface::class);
        $services = $container->findTaggedServiceIds('tug_seo.field');

        foreach ($services as $id => $tags) {
            $definition->addMethodCall('set', [new Reference($id)]);
        }
    }
}
