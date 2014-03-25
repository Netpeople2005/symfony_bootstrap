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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Description of TwigConfig
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class TwigConfiguration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('twig');

        //$this->addFormSection($rootNode);
        $this->addGlobalsSection($rootNode);
        $this->addTwigOptions($rootNode);

        return $treeBuilder;
    }

    private function addFormSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->fixXmlConfig('resource')
                    ->children()
                        ->arrayNode('resources')
                            ->addDefaultChildrenIfNoneSet()
                            ->prototype('scalar')->defaultValue('form_div_layout.twig')->end()
                            ->example(array('form.twig'))
                            ->validate()
                                ->ifTrue(function ($v) { return !in_array('form_div_layout.twig', $v); })
                                ->then(function ($v) {
                                    return array_merge(array('form_div_layout.twig'), $v);
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGlobalsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('global')
            ->children()
                ->arrayNode('globals')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('key')
                    ->example(array('foo' => '"@bar"', 'pi' => 3.14))
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifTrue(function ($v) { return is_string($v) && 0 === strpos($v, '@'); })
                            ->then(function ($v) {
                                if (0 === strpos($v, '@@')) {
                                    return substr($v, 1);
                                }

                                return array('id' => substr($v, 1), 'type' => 'service');
                            })
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(function ($v) {
                                if (is_array($v)) {
                                    $keys = array_keys($v);
                                    sort($keys);

                                    return $keys !== array('id', 'type') && $keys !== array('value');
                                }

                                return true;
                            })
                            ->then(function ($v) { return array('value' => $v); })
                        ->end()
                        ->children()
                            ->scalarNode('id')->end()
                            ->scalarNode('type')
                                ->validate()
                                    ->ifNotInArray(array('service'))
                                    ->thenInvalid('The %s type is not supported')
                                ->end()
                            ->end()
                            ->variableNode('value')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTwigOptions(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('path')
            ->children()
                ->scalarNode('autoescape')->end()
                ->scalarNode('base_template_class')->example('Twig_Template')->end()
                ->scalarNode('cache')->defaultValue('%cache_dir%twig')->end()
                ->scalarNode('charset')->defaultValue('%charset%')->end()
                ->scalarNode('debug')->defaultValue('%debug%')->end()
                ->scalarNode('strict_variables')->end()
                ->scalarNode('auto_reload')->end()
                ->scalarNode('optimizations')->end()
                ->arrayNode('paths')
                    ->normalizeKeys(false)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($paths) {
                            $normalized = array();
                            foreach ($paths as $path => $namespace) {
                                if (is_array($namespace)) {
                                    // xml
                                    $path = $namespace['value'];
                                    $namespace = $namespace['namespace'];
                                }

                                // path within the default namespace
                                if (ctype_digit((string) $path)) {
                                    $path = $namespace;
                                    $namespace = null;
                                }

                                $normalized[$path] = $namespace;
                            }

                            return $normalized;
                        })
                    ->end()
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }

}
