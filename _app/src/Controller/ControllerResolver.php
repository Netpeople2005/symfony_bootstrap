<?php

namespace Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseResolver;

class ControllerResolver extends BaseResolver
{

    /**
     *
     */
    protected $rootDir;

    public function __construct($rootDir, LoggerInterface $logger = null)
    {
        $this->rootDir = $rootDir;
        parent::__construct($logger);
    }

    public function getController(Request $request)
    {
        if (!$request->attributes->has('_file')) {
            return parent::getController($request);
        }

        $attributes = $request->attributes->all();

        $__file = $this->rootDir . $attributes['_file'];

        return function() use($__file) {
                    //acá van los globals :-s
                    return require $__file;
                };
    }

}
