<?php

namespace Controllers;

use Model\Abonos;
use Model\Bloqueos;
use Model\Creditos;
use Model\Personas;
use Model\Usuarios;

class ApiController
{
    public static function eliminar_usuario()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $usuario = Usuarios::find($id);

            if (empty($usuario)) {
                $respuesta = Creditos::eliminar_registros('id_persona', $id);
                $respuesta = Abonos::eliminar_registros('id_persona', $id);
                $respuesta = Bloqueos::eliminar_registros('id', $id);
                $respuesta = Personas::eliminar_registros('id', $id);
                $respuesta = 'Usuario eliminado con exito';
            } else {
                $respuesta = 'No se puede eliminar un usuario administrador, primero 
                realice la conversion a otro perfil.';
            }

            echo json_encode($respuesta);
        }
    }

    public static function actualiza_bloqueo()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $perfil = Usuarios::find($id);
            $bloqueo = Bloqueos::find($id);
            $array_resp = [];

            if ((empty($bloqueo)) && (empty($perfil))) {
                $creditos = Creditos::sumaTotales('credito', 'id_persona', $id);
                $abonos = Abonos::sumaTotales('abono', 'id_persona', $id);
                $total = ($creditos ?? 0) - ($abonos ?? 0);
                if ($total > 0) {
                    $respuesta = Bloqueos::agregar_bloqueos($id, date('Y-m-d'), $total);
                } else {
                    $respuesta = Bloqueos::agregar_bloqueos($id, date('Y-m-d'), 0);
                }
                $array_resp = [1, 'Usuario inhabilitado con exito'];
            } else {
                $respuesta = Bloqueos::eliminar_registros('id', $id);
                $array_resp = [0, 'Usuario habilitado con exito'];
            }

            echo json_encode($array_resp);
        }
    }

    public static function nombre_usuario()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $persona = Personas::find($id);
        }
        echo json_encode($persona);
    }

    public static function ingresar_credito()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $fecha = $_POST['fecha'];
            $valor = $_POST['valor'];

            $credito = Creditos::agregar_credito($id, $fecha, $valor);
        }
        echo json_encode($credito);
    }

    public static function informe_deudores()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        $personas = Personas::all();
        $listDeudores = [];

        foreach ($personas as $persona) {
            $creditos = Creditos::sumaTotales('credito', 'id_persona', $persona->id);
            $abonos = Abonos::sumaTotales('abono', 'id_persona', $persona->id);
            $total = ($creditos ?? 0) - ($abonos ?? 0);

            if ($total > 0) {
                $persona->deuda = $total; // Agregar la deuda como una propiedad de la persona
                $listDeudores[] = $persona; // Agregar la persona al array
            }
        }

        // Ordenar el array por deuda de mayor a menor
        usort($listDeudores, function ($a, $b) {
            return $b->deuda <=> $a->deuda; // Orden descendente
        });

        echo json_encode($listDeudores);
    }

    public static function ingresar_abono()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $respuesta;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $fecha = $_POST['fecha'];
            $valor = $_POST['valor'];

            $abono = Abonos::agregar_abono($id, $fecha, $valor);

            $bloqueo = Bloqueos::find($id);
            if ($bloqueo) {

                $fecha_bloqueo = $bloqueo->fecha_bloqueo;
                $saldo = $bloqueo->saldo - $valor;
                if ($saldo <= 0) {
                    $respuesta = $bloqueo->eliminar_registros('id', $id);
                    $respuesta = Bloqueos::agregar_bloqueos($id, $fecha_bloqueo, 0);
                } else {
                    $respuesta = $bloqueo->eliminar_registros('id', $id);
                    $respuesta = Bloqueos::agregar_bloqueos($id, $fecha_bloqueo, $saldo);
                }
            }
        }
        echo json_encode($abono);
    }

    public static function consultaBloqueo()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $bloqueo = Bloqueos::find($id);
        }

        echo json_encode($bloqueo);
    }

    public static function consultaDeuda()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $creditos = Creditos::sumaTotales('credito', 'id_persona', $id);
            $abonos = Abonos::sumaTotales('abono', 'id_persona', $id);
            $total = ($creditos ?? 0) - ($abonos ?? 0);
        }
        echo json_encode($total);
    }

    public static function modificarCredito()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $valor = $_POST['valor'];

            $cliente = Creditos::find($id);
            $creditos = Creditos::actualizarDato($id, $valor, 'credito');

            $bloqueo = Bloqueos::find($cliente->id_persona);

            if ($bloqueo) {
                $creditos = Creditos::sumaTotales('credito', 'id_persona', $cliente->id_persona);
                $abonos = Abonos::sumaTotales('abono', 'id_persona', $cliente->id_persona);
                $total = ($creditos ?? 0) - ($abonos ?? 0);
                if ($total > 0) {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), $total);
                } else {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), 0);
                }
            }
        }
        echo json_encode($creditos);
    }

    public static function eliminarCredito()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $cliente = Creditos::find($id);

            $creditos = Creditos::eliminar_registros('id', $id);

            $bloqueo = Bloqueos::find($cliente->id_persona);

            if ($bloqueo) {
                $creditos = Creditos::sumaTotales('credito', 'id_persona', $cliente->id_persona);
                $abonos = Abonos::sumaTotales('abono', 'id_persona', $cliente->id_persona);
                $total = ($creditos ?? 0) - ($abonos ?? 0);
                if ($total > 0) {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), $total);
                } else {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), 0);
                }
            }
        }
        echo json_encode($creditos);
    }

    public static function modificarAbono()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $valor = $_POST['valor'];
            $cliente = Abonos::find($id);
            $creditos = Abonos::actualizarDato($id, $valor, 'abono');

            $bloqueo = Bloqueos::find($cliente->id_persona);

            if ($bloqueo) {
                $creditos = Creditos::sumaTotales('credito', 'id_persona', $cliente->id_persona);
                $abonos = Abonos::sumaTotales('abono', 'id_persona', $cliente->id_persona);
                $total = ($creditos ?? 0) - ($abonos ?? 0);
                if ($total > 0) {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), $total);
                } else {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), 0);
                }
            }
        }
        echo json_encode($creditos);
    }

    public static function eliminarAbono()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $cliente = Abonos::find($id);
            $creditos = Abonos::eliminar_registros('id', $id);

            if ($bloqueo) {
                $creditos = Creditos::sumaTotales('credito', 'id_persona', $cliente->id_persona);
                $abonos = Abonos::sumaTotales('abono', 'id_persona', $cliente->id_persona);
                $total = ($creditos ?? 0) - ($abonos ?? 0);
                if ($total > 0) {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), $total);
                } else {
                    $respuesta = $bloqueo->eliminar_registros('id', $cliente->id_persona);
                    $respuesta = Bloqueos::agregar_bloqueos($cliente->id_persona, date('Y-m-d'), 0);
                }
            }
        }
        echo json_encode($creditos);
    }

    public static function filtroPersonas()
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $personas = Personas::all();

        foreach ($personas as $persona) {
            $persona->perfil = 'Habilitado';
            if (Bloqueos::find($persona->id)) {
                $persona->perfil = 'Inhabilitado';
            }
        }

        echo json_encode($personas);
    }

    public static function registro_credito()
    {
        $email = $_POST['email'];
        $persona = Personas::where('email', $email);
        $id = $persona[0]->id;

        $creditos = Creditos::sumaTotales('credito', 'id_persona', $id);
        $deuda = Creditos::where('id_persona', $id);
        $abonos = Abonos::sumaTotales('abono', 'id_persona', $id);
        $registros = [];

        $total_deuda = ($creditos ?? 0) - ($abonos ?? 0);

        foreach ($deuda as $registro) {
            if ($abonos > $registro->credito) {
                $abonos -= $registro->credito;
            } else {
                if ($abonos > 0) {
                    $registro->credito = $registro->credito - $abonos;
                    $abonos = 0;
                }

                if ($registro->credito > 0) {
                    $registros[] = $registro;
                }
            }
        }

        // Ordenar el array por deuda de mayor a menor
        usort($registros, function ($a, $b) {
            return $b->fecha <=> $a->fecha; // Orden descendente
        });

        $credito_usuario = [$registros, $persona, $total_deuda];

        echo json_encode($credito_usuario);
    }
}
