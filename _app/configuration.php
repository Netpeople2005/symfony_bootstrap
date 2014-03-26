<?php

/* @var $app App */

use Application\DependencyInjection\Compiler\LoggerCompilerPass;
use Application\DependencyInjection\Compiler\TwigCompilerPass;
use Application\DependencyInjection\Extension\ApplicationExtension;
use Application\DependencyInjection\Extension\TwigExtension;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterListenersPass;

return array(
    'extensions' => array(
        new ApplicationExtension(),
        new TwigExtension(),
    ),
    'compilers' => array(
        new TwigCompilerPass(),
        new RegisterListenersPass(),
        new LoggerCompilerPass(),
    ),
);

