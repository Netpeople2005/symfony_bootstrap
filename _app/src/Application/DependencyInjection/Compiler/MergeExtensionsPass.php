<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class MergeExtensionsPass extends MergeExtensionConfigurationPass
{

    public function process(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            if (!count($container->getExtensionConfig($name))) {
                //cargamos solo las extensiones que no poseen configuración, 
                //ya que la clase padre cargará las que si poseen config.
                $container->loadFromExtension($name, array());
            }
        }

        parent::process($container);
    }

}
