<?php

namespace Omouren\ExternalVarDumperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('omouren_external_var_dumper');

        $rootNode
            ->children()
                ->scalarNode('app_name')->defaultValue('Symfony')->end()
                ->scalarNode('uri')->defaultValue('http://localhost:1337')->end()
                ->enumNode('method')
                    ->values(array('get', 'post', 'head', 'put', 'delete', 'options', 'patch'))
                    ->defaultValue('post')
                ->end()
                ->booleanNode('enabled')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
