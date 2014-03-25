<?php

namespace Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of TwigCompilerPass
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class TwigCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('twig.extension');

        $twig = $container->getDefinition('twig');
        foreach ($services as $id => $config) {
            $twig->addMethodCall('addExtension', array( new Reference($id)));
        }
    }

}
