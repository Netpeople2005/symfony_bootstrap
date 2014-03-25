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

use Application\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class TwigExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        foreach ($configs as &$config) {
            if (isset($config['globals'])) {
                foreach ($config['globals'] as $name => $value) {
                    if (is_array($value) && isset($value['key'])) {
                        $config['globals'][$name] = array(
                            'key' => $name,
                            'value' => $config['globals'][$name]
                        );
                    }
                }
            }
        }

        $twigConfig = $this->getConfiguration($configs, $container);
        $configs = $this->processConfiguration($twigConfig, $configs);

        $loader = new YamlFileLoader($container, new FileLocator($this->getConfigDir()));
        $loader->load('services/twig.yml');

        $this->addPaths($configs, $container);
        $this->addGlobals($configs, $container);

        unset($configs['globals'], $configs['paths']);

        $container->setParameter('twig.options', $configs);
    }

    public function getAlias()
    {
        return 'twig';
    }

    protected function addPaths(array $config, ContainerBuilder $container)
    {
        $loader = $container->getDefinition('twig.loader.filesystem');
        foreach ($config['paths'] as $path => $namespace) {
            if ($namespace) {
                $loader->addMethodCall('addPath', array($path, $namespace));
            } else {
                $loader->addMethodCall('addPath', array($path));
            }
        }
    }

    protected function addGlobals(array $config, ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig');

        if (!empty($config['globals'])) {
            $def = $container->getDefinition('twig');
            foreach ($config['globals'] as $key => $global) {
                if (isset($global['type']) && 'service' === $global['type']) {
                    $def->addMethodCall('addGlobal', array($key, new Reference($global['id'])));
                } else {
                    $def->addMethodCall('addGlobal', array($key, $global['value']));
                }
            }
        }
    }

}
