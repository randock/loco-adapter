<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\PlatformAdapter\Loco\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('translation_adapter_loco');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->scalarNode('httplug_client')->defaultNull()->end()
                ->scalarNode('httplug_message_factory')->defaultNull()->end()
                ->scalarNode('httplug_uri_factory')->defaultNull()->end()
                ->scalarNode('index_parameter')
                    ->info('Index parameter sent to loco api to all your domains. Specify whether file indexes translations by asset ID or source texts')
                    ->example('id')
                    ->defaultNull()
                ->end()
                ->append($this->getProjectNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * @return NodeDefinition
     */
    private function getProjectNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('projects');
        $node = $treeBuilder->getRootNode();
        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('status')->defaultValue('translated')->end()
                ->scalarNode('index_parameter')
                    ->info('Index parameter sent to loco api for this particular domain (overrides global one). Specify whether file indexes translations by asset ID or source texts')
                    ->example('id')
                    ->defaultNull()
                ->end()
                ->arrayNode('domains')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ->end();

        return $node;
    }
}
