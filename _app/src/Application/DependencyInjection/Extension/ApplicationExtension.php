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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ApplicationExtension extends Extension
{

    protected $rootDir;
    protected $isDebug;

    public function __construct($rootDir, $isDebug)
    {
        $this->rootDir = $rootDir;
        $this->isDebug = $isDebug;
    }

    public function load(array $config, ContainerBuilder $container)
    {
        //ahora es en la extensión donde establecemos los parametros
        $container->setParameter('root_dir', $this->rootDir);
        $container->setParameter('debug', $this->isDebug);
    }

}
