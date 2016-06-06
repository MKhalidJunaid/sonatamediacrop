<?php

namespace Media\CroppingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('media_cropping');
	    $rootNode
	         ->children()
	             ->arrayNode('sizes')
	                 ->isRequired()
	                 ->useAttributeAsKey('id')
	                 ->prototype('array')
	                     ->children()
	                         ->scalarNode('width')->defaultValue(false) ->isRequired()->end()
	                         ->scalarNode('height')->defaultValue(false) ->isRequired()->end()
	                         ->scalarNode('quality')->defaultValue(80) ->isRequired()->end()
	                     ->end()
	                 ->end()
	             ->end()
	         ->end();
        return $treeBuilder;
    }

}
