<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lexik_cron_file_generator');
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('lexik_cron_file_generator');

        $rootNode
            ->children()
                ->arrayNode('env_available')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                    ->isRequired()
                    ->end()
                ->end()
                ->arrayNode('user')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode('php_version')->isRequired()->end()
                ->arrayNode('absolute_path')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                        ->isRequired()
                    ->end()
                ->end()
                ->scalarNode('output_path')->isRequired()->end()
                ->arrayNode('crons')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('command')->isRequired()->end()
                            ->arrayNode('env')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')
                                ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
