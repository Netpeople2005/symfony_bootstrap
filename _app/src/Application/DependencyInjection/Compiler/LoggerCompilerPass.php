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
        $debug = $container->getParameter('debug');

        $services = $container->findTaggedServiceIds('logger.handler');

        $definition = $container->getDefinition('logger');
        foreach ($services as $id => $config) {
            
            if (isset($config['debug']) and !$debug) {
                continue;
            }
            
            //solo registramos si es debug o si el handler no es condicionado a debug
            
            $definition->addMethodCall('pushHandler', array(new Reference($id)));
        }
    }

}
