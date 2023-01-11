<?php

namespace Tug\SeoBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Tug\SeoBundle\DependencyInjection\Compiler\{FieldRegistryPass, RendererRegistryPass, TranslatableFieldPass};
use Tug\SeoBundle\Field\{FieldInterface, TranslatableFieldInterface};
use Tug\SeoBundle\Renderer\RendererInterface;

class TugSeoBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(RendererInterface::class)
            ->addTag('tug_seo.renderer');

        $container->registerForAutoconfiguration(FieldInterface::class)
            ->addTag('tug_seo.field');

        $container->registerForAutoconfiguration(TranslatableFieldInterface::class)
            ->addTag('tug_seo.field.translatable');

        $container->addCompilerPass(new RendererRegistryPass());

        $container->addCompilerPass(new FieldRegistryPass());

        $container->addCompilerPass(new TranslatableFieldPass());
    }


    public function getPath(): string
    {
        return __DIR__;
    }
}
