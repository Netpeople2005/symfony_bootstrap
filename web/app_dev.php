<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$app = new App('dev', true);

$request->attributes->add(array(
    '_file' => 'test.php',
));

$app->run($request);
