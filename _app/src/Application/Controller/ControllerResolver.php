<?php

namespace Application\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseResolver;

class ControllerResolver extends BaseResolver
{

    /**
     *
     */
    protected $rootDir;
    protected $globals;

    public function __construct($rootDir, array $globals = array(), LoggerInterface $logger = null)
    {
        $this->rootDir = $rootDir;
        $this->globals = $globals;
        parent::__construct($logger);
    }

    public function getController(Request $request)
    {
        if (!$request->attributes->has('_file')) {
            return parent::getController($request);
        }

        $attributes = $request->attributes->all();

        $__file = $this->rootDir . '/' . $attributes['_file'];

        $__globals = $this->globals;

        return function() use($__file, $__globals) {
            //acá van los globals :-s
            foreach ($__globals as $__global) {
                //con esto hacemos las variables globales accesibles al archivo a cargar.
                //el arreglo $__globals contiene los nombres de las variables.
                // al usar doble $$ estamos accediento a una variable desde el string de otra.
                global $$__global;
            }

            return require $__file;
        };
    }

}
