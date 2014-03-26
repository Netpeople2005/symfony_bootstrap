<?php

namespace Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of LoggerCompillerPass
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class LoggerCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('debug')) {
            $logger = $container->getDefinition('logger');
            
            $container->register('logger.handler.phpfire', 'Monolog\\Handler\\FirePHPHandler');
            $container->register('logger.handler.chromephp', 'Monolog\\Handler\\ChromePHPHandler');
            
            $logger->addMethodCall('pushHandler', array(new Reference('logger.handler.phpfire')));
            $logger->addMethodCall('pushHandler', array(new Reference('logger.handler.chromephp')));
        }
    }

}
