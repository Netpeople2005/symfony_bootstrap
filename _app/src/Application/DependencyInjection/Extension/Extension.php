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

use ReflectionClass;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
abstract class Extension extends BaseExtension
{

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $reflected = new ReflectionClass($this);
        $namespace = $reflected->getNamespaceName();
        $className = preg_replace('/(.+)Extension/', '$1Configuration', $reflected->getShortName());

        $class = $namespace . '\\' . $className;
        if (class_exists($class)) {
            $r = new ReflectionClass($class);
            $container->addResource(new FileResource($r->getFileName()));

            if (!method_exists($class, '__construct')) {
                $configuration = new $class();

                return $configuration;
            }
        }

        return null;
    }

}
