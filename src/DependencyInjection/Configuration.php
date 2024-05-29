<?php

namespace Tug\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\{ArrayNodeDefinition, TreeBuilder};
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Tug\SeoBundle\Translate\TranslationType;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tug_seo');

        $treeBuilder->getRootNode()
            ->children()
                ->append($this->getJsonLdDefinition())
                ->append($this->getTranslationDefinition())
                ->append($this->getOptionsDefinition())
                ->append($this->getParametersDefinition())
                ->variableNode('default')->end()
                ->variableNode('routes')->end()
                ->arrayNode('hierarchy')
                    ->normalizeKeys(false)
                    ->arrayPrototype()
                    ->scalarPrototype()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

    public function getJsonLdDefinition(): ArrayNodeDefinition
    {
        $treeBuilder = new ArrayNodeDefinition('jsonLd');

        return $treeBuilder->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('context')
                    ->defaultNull()
                ->end()
                ->arrayNode('types')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('context')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('name')->end()
                            ->arrayNode('parents')
                                ->defaultValue([])
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('fields')
                                ->defaultValue([])
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function getTranslationDefinition(): ArrayNodeDefinition
    {
        $treeBuilder = new ArrayNodeDefinition('translation');

        return $treeBuilder->addDefaultsIfNotSet()
            ->children()
                ->enumNode('type')
                    ->values(TranslationType::cases())
                    ->defaultValue(TranslationType::None)
                    ->beforeNormalization()
                    ->ifString()->then(fn($value) => TranslationType::from($value))->end()
                ->end()
                ->scalarNode('domain')
                    ->defaultValue('tug_seo')
                ->end()
                ->arrayNode('format_template')
                    ->defaultValue(['{', '}'])
                    ->scalarPrototype()
                ->end()
            ->end()
        ->end();
    }

    public function getOptionsDefinition(): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition('options'))
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('default')
                    ->defaultValue([])
                ->end()
                ->variableNode('routes')
                    ->defaultValue([])
                ->end()
            ->end();
    }

    public function getParametersDefinition(): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition('parameters'))
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('global')
                    ->defaultValue([])
                ->end()
                ->variableNode('default')
                    ->defaultValue([])
                ->end()
                ->variableNode('routes')
                    ->defaultValue([])
                ->end()
            ->end();
    }
}
