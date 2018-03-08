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
                ->scalarNode('restapi')->end()
                ->scalarNode('graphql')->end()
                ->scalarNode('version')->end()
                ->scalarNode('versions')->end()
            ->end();
        return $treeBuilder;
    }
}
