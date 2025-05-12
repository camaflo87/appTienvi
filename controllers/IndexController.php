<?php

namespace Controllers;

use Model\Personas;
use MVC\Router;

class IndexController
{
    public static function consultaClientes(Router $router)
    {
        // Render a la vista 
        $router->render('principal/consultaIndex', [
            'titulo' => 'Index TienVi',
            'admin' => 'Administrador',
            'link' => '/login'
        ]);
    }
}
