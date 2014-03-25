<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\DependencyInjection\Extension;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Description of ApplicationConfiguration
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ApplicationConfiguration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('application');

        $rootNode->children()
                    ->scalarNode('controller_dir')->end()
                    ->arrayNode('globals')
                        ->prototype('scalar')
                    ->end()
                ->end();

        return $treeBuilder;
    }

}
