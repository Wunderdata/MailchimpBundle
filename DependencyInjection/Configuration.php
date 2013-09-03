<?php

namespace Wunderdata\MailchimpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wunderdata_mailchimp');
        $rootNode->children()
            ->scalarNode('apikey')
                ->isRequired()
                ->cannotBeEmpty()
                ->end()
            ->arrayNode('opts')->children()
                ->integerNode('timeout')
                    ->defaultValue(600)
                    ->end()
                ->booleanNode('debug')
                    ->defaultFalse()
                    ->end()
                ->end();
        return $treeBuilder;
    }
}