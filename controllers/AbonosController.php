<?php

namespace Controllers;

use MVC\Router;
use Model\Abonos;
use Model\Creditos;
use Model\Personas;


class AbonosController
{
    public static function abonos_persona(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $id = $_GET['id'];

        $persona = Personas::find($id);

        $abonos = Abonos::abonosUsuario($id);

        $total = 0;
        $cant = 0;

        $nombre = "$persona->nombre $persona->apellido";

        foreach ($abonos as $dato) {
            $total = $total + $dato->abono;
        }

        $cant = count($abonos);

        $router->render('abonos/registro_abonosUser', [
            'titulo' => 'Registro de Abonos',
            'nombre' => $nombre,
            'total' => $total,
            'cant' => $cant,
            'abonos' => $abonos
        ]);
    }
}
