<?php

/* @var $app App */

use Application\DependencyInjection\Compiler\TwigCompilerPass;
use Application\DependencyInjection\Extension\ApplicationExtension;
use Application\DependencyInjection\Extension\TwigExtension;

return array(
    'extensions' => array(
        new ApplicationExtension(),
        new TwigExtension(),
    ),
    'compilers' => array(
        new TwigCompilerPass(),
    ),
);

