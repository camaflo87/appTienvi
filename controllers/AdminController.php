<?php

namespace Controllers;

use Classes\Paginacion;
use DateTime;
use MVC\Router;
use Model\Abonos;
use Model\Bloqueos;
use Model\Creditos;
use Model\Personas;
use Model\Usuarios;

class AdminController
{
    public static function index(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $listDeudores = [];
        $topDeudores = [];
        $listMorosos = [];
        $listAbonos = [];
        $list_bloqueados = [];
        $totalFavor = 0;

        // Cantidad clientes
        $personas = Personas::all();
        $cant = count($personas); // Dato a consultar

        // Credito actual
        $creditos = Creditos::suma_total_campo('credito');
        $abonos = Abonos::suma_total_campo('abono');
        $totalGeneral = ($creditos ?? 0) - ($abonos ?? 0); // Dato a consultar

        // Credito hoy
        $fecha_php = date('Y-m-d');
        $deudaHoy = Creditos::creditoHoy($fecha_php); // Dato a consultar

        // Top 10 deudores
        $personas = Personas::all();

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

        for ($i = 0; $i <= 9; $i++) {
            if (!isset($listDeudores[$i])) {
                break; // Detener el ciclo si el índice no existe
            }
            if ($listDeudores[$i] === NULL) {
                break; // Detener el ciclo si se encuentra un valor NULL
            } else {
                $topDeudores[$i] = $listDeudores[$i];
            }
        }

        // Clientes morosos mas de 30 días
        foreach ($personas as $persona) {
            $deuda = Creditos::where('id_persona', $persona->id);
            $abonosTotales = Abonos::sumaTotales('abono', 'id_persona', $persona->id);

            foreach ($deuda as $registro) {
                if ($abonosTotales >= $registro->credito) {
                    $abonosTotales -= $registro->credito;
                } else {

                    $fechaInicio = new DateTime($registro->fecha);
                    $fechaActual = new DateTime(date("Y-m-d"));

                    // Calcula la diferencia entre las dos fechas
                    $intervalo = $fechaInicio->diff($fechaActual);

                    // Obtiene la diferencia en días
                    $dias = $intervalo->days;

                    // Si los días son mayores o iguales a 30, los almacena en la lista de morosos.
                    if ($dias >= 30) {
                        $listMorosos[] = [
                            'nombre' => recortarTexto($persona->nombre) . " " . recortarTexto($persona->apellido),
                            'mora' => $dias
                        ];
                        break;
                    }
                }
            }
        }



        // Ordenar el array por perfil
        usort($listMorosos, function ($a, $b) {
            return strcmp($b["mora"], $a["mora"]);
        });

        // Clientes bloqueados
        $bloqueados = Bloqueos::all();

        foreach ($bloqueados as $bloqueo) {
            $usuario = Personas::find($bloqueo->id);
            $list_bloqueados[] = [
                'nombre' => recortarTexto($usuario->nombre) . " " . recortarTexto($usuario->apellido),
                'fecha' => $bloqueo->fecha_bloqueo,
                'saldo' => $bloqueo->saldo
            ];
        }

        // Ordenar el array por perfil
        usort($list_bloqueados, function ($a, $b) {
            return strcmp($b["saldo"], $a["saldo"]);
        });

        // Saldo a favor
        foreach ($personas as $persona) {
            $abonosAcumulados = Abonos::where('id_persona', $persona->id);
            $CreditosTotales = Creditos::sumaTotales('credito', 'id_persona', $persona->id);
            $saldoFavor = 0;

            foreach ($abonosAcumulados as $registro) {
                $CreditosTotales -= $registro->abono;
            }
            $saldoFavor = 0;
            $saldoFavor += ($CreditosTotales * -1);

            if ($saldoFavor > 0) {
                $totalFavor += $saldoFavor;
                $listAbonos[] = [
                    'nombre' => recortarTexto($persona->nombre) . " " . recortarTexto($persona->apellido),
                    'abono' => $saldoFavor
                ];
            }
        }

        // Ordenar el array por perfil
        usort($listAbonos, function ($a, $b) {
            return strcmp($b["abono"], $a["abono"]);
        });

        // Administradores
        $user_admin = Usuarios::all();

        foreach ($user_admin as $user) {
            $usuario = Personas::find($user->id);
            $perfil = $user->perfil;

            $list_users[] = [
                'usuario' => recortarTexto($usuario->nombre) . " " . recortarTexto($usuario->apellido),
                'perfil' => ($perfil === '2') ? 'Administrador' : 'Usuario'
            ];
        }

        // Ordenar el array por perfil
        usort($list_users, function ($a, $b) {
            return strcmp($a["perfil"], $b["perfil"]);
        });

        $router->render('admin/index_admin', [
            'titulo' => 'Administración Creditos',
            'link' => 'admin',
            'cant' => $cant,
            'total' => $totalGeneral,
            'deudaHoy' => $deudaHoy,
            'Afavor' => $listAbonos,
            'topDeudores' => $topDeudores,
            'morosos' => $listMorosos,
            'bloqueados' => $list_bloqueados,
            'administradores' => $list_users,
            'saldo' => $totalFavor
        ]);
    }

    public static function consulta_personalizada(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $titulo = '';
        $link = '';
        $pagina = '';
        $modal = '';
        $categoria = $_GET['cat'];

        // Area paginación
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 0) {
            header('Location: /administracion_consultas?cat=' . $categoria . '&page=1');
        }

        $registros_por_pagina = 12;
        $total = Personas::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total, $categoria);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /administracion_consultas?cat=' . $categoria . '&page=1');
        }

        // Consulto a todas las personas
        $personas = Personas::paginar($registros_por_pagina, $paginacion->offset());

        // Consultar personas
        if ($categoria === 'personas') {
            $titulo = 'Consultar Persona';
            $link = "persona";
            $pagina = '/modificar';
            $textoBtn = '';
        }

        // Consultar creditos
        if ($categoria === 'creditos') {
            $titulo = 'Consultar Credito';
            $link = "credito";
            $textoBtn = 'Consultar';
            $pagina = '/creditos_individuales';
        }

        // Agregar credito
        if ($categoria === 'agrCredito') {
            $titulo = 'Agregar Credito';
            $link = "agrcredito";
            $modal = 'btnAgregarCredito';
            $textoBtn = 'Credito';
        }

        // Agregar pago
        if ($categoria === 'regPago') {
            $titulo = 'Agregar Pago';
            $link = "agrPago";
            $modal = 'btnAgregarPago';
            $textoBtn = 'Abono';
        }

        // Consultar pago
        if ($categoria === 'consultaPago') {
            $titulo = 'Consultar Pagos';
            $link = "cstPago";
            $textoBtn = 'Consultar';
            $pagina = '/abonos_individuales';
        }

        // Bloquear pago
        if ($categoria === 'bloquear') {
            $titulo = 'Area de Inhabilitación de Creditos';
            $link = "userBloq";
            $modal = 'btnBloqueos';
            $textoBtn = '';

            foreach ($personas as $persona) {
                $persona->perfil = 'Habilitado';
                $respuesta = Bloqueos::find($persona->id);

                if ($respuesta) {
                    $persona->perfil = 'Inhabilitado';
                }
            }
        }


        $router->render('admin/consulta_estandar', [
            'titulo' => $titulo,
            'link' => $link,
            'personas' => $personas,
            'categoria' => $categoria,
            'pagina' => $pagina,
            'modal' => $modal,
            'texto' => $textoBtn,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
}
