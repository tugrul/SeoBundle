<?php

namespace Tug\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Serializer\SerializerInterface;

class SerializableFieldPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $serializer = new Reference(SerializerInterface::class);

        $services = $container->findTaggedServiceIds('tug_seo.field.serializable');

        foreach ($services as $id => $tags) {
            $container->getDefinition($id)->addMethodCall('setSerializer', [$serializer]);
        }
    }

}
