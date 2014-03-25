<?php
/* @var $app App */

use Application\DependencyInjection\Extension\ApplicationExtension;

return array(
    'extensions' => array(
        new ApplicationExtension($app->getRootDir(), $app->getDebug()),
    ),
    'compilers' => array(
    ),
);

