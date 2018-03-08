<?php
namespace Olla\Core\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('olla');
        $rootNode
            ->children()
                ->scalarNode('title')->defaultValue('Prisma')->end()
                ->scalarNode('description')->defaultValue('')->end()
                ->scalarNode('version')->defaultValue('0.0.0')->end()
                ->scalarNode('cache_dir')->defaultValue('%kernel.project_dir%/var/prisma')->end()
                ->arrayNode('controllers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('frontend')->end()
                        ->scalarNode('tool')->end()
                        ->scalarNode('admin')->end()
                        ->scalarNode('restapi')->end()
                    ->end()
                ->end()
                ->arrayNode('formats')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('frontend')->end()
                        ->scalarNode('tool')->end()
                        ->scalarNode('admin')->end()
                        ->scalarNode('restapi')->end()
                    ->end()
                ->end()
                ->arrayNode('prefixs')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('frontend')->defaultValue('/')->end()
                        ->scalarNode('tool')->defaultValue('/')->end()
                        ->scalarNode('admin')->defaultValue('/admin')->end()
                        ->scalarNode('restapi')->defaultValue('/api')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
