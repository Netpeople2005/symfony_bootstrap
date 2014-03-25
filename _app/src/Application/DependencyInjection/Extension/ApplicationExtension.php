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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ApplicationExtension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($config, $container);
        $config = $this->processConfiguration($configuration, $config);

        $loader = new YamlFileLoader($container, new FileLocator($this->getConfigDir()));
        $loader->load('services/app.yml');

        $this->configControllerResolver($config, $container);
    }

    protected function configControllerResolver(array $config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('controller_resolver')) {
            return;
        }

        $definition = $container->getDefinition('controller_resolver');

        $definition->replaceArgument('0', $config['controller_dir']);
        $definition->replaceArgument('1', $config['globals']);
    }

}
