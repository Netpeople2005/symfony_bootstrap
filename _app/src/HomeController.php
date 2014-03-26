<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Description of HomeController
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class HomeController
{

    public function indexAction($name)
    {
        return App::render('home.twig', compact('name'));
    }

}
