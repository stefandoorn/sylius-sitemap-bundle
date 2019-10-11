<?php

declare(strict_types=1);

namespace SitemapPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_sitemap');

        $this->addSitemapSection($rootNode);

        return $treeBuilder;
    }

    private function addSitemapSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('products')->defaultTrue()->end()
                        ->booleanNode('taxons')->defaultTrue()->end()
                        ->booleanNode('static')->defaultTrue()->end()
                    ->end()
                ->end()
                ->scalarNode('template')
                    ->defaultValue('@SitemapPlugin/show.xml.twig')
                ->end()
                ->scalarNode('index_template')
                    ->defaultValue('@SitemapPlugin/index.xml.twig')
                ->end()
                ->scalarNode('exclude_taxon_root')
                    ->info('Often you don\'t want to include the root of your taxon tree as it has a generic name as \'products\'.')
                    ->defaultTrue()
                ->end()
                ->scalarNode('absolute_url')
                    ->info('Whether to generate absolute URL\'s (true) or relative (false). Defaults to true.')
                    ->defaultTrue()
                ->end()
                ->scalarNode('hreflang')
                    ->info('Whether to generate alternative URL versions for each locale. Defaults to true. Background: https://support.google.com/webmasters/answer/189077?hl=en.')
                    ->defaultTrue()
                ->end()
                ->arrayNode('static_routes')
                    ->beforeNormalization()->castToArray()->end()
                    ->info('In case you want to add static routes to your sitemap (e.g. homepage), configure them here. Defaults to homepage & contact page.')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('route')
                                ->info('Name of route')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('parameters')
                                ->prototype('variable')->end()
                                ->info('Add optional parameters to the route.')
                            ->end()
                            ->arrayNode('locales')
                                ->prototype('scalar')->end()
                                ->info('Define which locales to add. If empty, it uses the default locales for channel context supplied')
                            ->end()
                            ->floatNode('priority')
                                ->min(0)
                                ->max(1)
                                ->defaultValue(0.3)
                                ->info('Define the the priority for the static route. Defaults to 0.3.')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
