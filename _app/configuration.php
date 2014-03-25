<?php

use Application\DependencyInjection\Extension\ApplicationExtension;

return array(
    'extensions' => array(
        new ApplicationExtension($rootDir, $debug),
    ),
    'compilers' => array(
    ),
);

