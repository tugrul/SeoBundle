<?php

namespace Tug\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Tug\SeoBundle\Translate\TranslatorInterface;

class TranslatableFieldPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $translator = new Reference(TranslatorInterface::class);

        $services = $container->findTaggedServiceIds('tug_seo.field.translatable');

        foreach ($services as $id => $tags) {
            $container->getDefinition($id)->addMethodCall('setTranslator', [$translator]);
        }
    }
}
