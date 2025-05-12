<?php

namespace Controllers;

use MVC\Router;
use Model\Abonos;
use Model\Creditos;
use Model\Personas;
use Dompdf\Dompdf;

class CreditosController
{
    public static function informe_deudores(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $personas = Personas::all();
        $listDeudores = [];
        $deuda_general = 0;

        foreach ($personas as $persona) {
            $creditos = Creditos::sumaTotales('credito', 'id_persona', $persona->id);
            $abonos = Abonos::sumaTotales('abono', 'id_persona', $persona->id);
            $total = ($creditos ?? 0) - ($abonos ?? 0);
            $deuda_general += $total; // Acumula la deuda total

            if ($total > 0) {
                $persona->deuda = $total; // Agregar la deuda como una propiedad de la persona
                $listDeudores[] = $persona; // Agregar la persona al array
            }
        }

        // debuguear($deuda_general);

        // Ordenar el array por deuda de mayor a menor
        usort($listDeudores, function ($a, $b) {
            return $b->deuda <=> $a->deuda; // Orden descendente
        });

        $cant = count($listDeudores);

        $router->render('creditos/informe_creditos', [
            'titulo' => 'Informe General de Deudores',
            'link' => 'informe',
            'deudores' => $listDeudores,
            'cant' => $cant,
            'deudaGeneral' => $deuda_general
        ]);
    }

    public static function credito_persona(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $id = $_GET['id'];

        $persona = Personas::find($id);
        $creditos = Creditos::sumaTotales('credito', 'id_persona', $id);
        $deuda = Creditos::where('id_persona', $id);
        $abonos = Abonos::sumaTotales('abono', 'id_persona', $id);
        $registros = [];

        $total = ($creditos ?? 0) - ($abonos ?? 0);
        $nombre = "$persona->nombre $persona->apellido";

        foreach ($deuda as $registro) {
            if ($abonos > $registro->credito) {
                $abonos -= $registro->credito;
            } else {
                $registro->credito = $registro->credito - $abonos;
                $abonos = 0;
                if ($registro->credito > 0) {
                    $registros[] = $registro;
                }
            }
        }

        // Ordenar el array por deuda de mayor a menor
        usort($registros, function ($a, $b) {
            return $b->fecha <=> $a->fecha; // Orden descendente
        });

        $cant = count($registros);

        $router->render('creditos/registro_creditoUser', [
            'titulo' => 'Creditos Pendientes',
            'nombre' => $nombre,
            'deuda' => $total,
            'registros' => $registros,
            'cant' => $cant
        ]);
    }

    public static function informe_pdf()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dompdf = new Dompdf();
            $html = $_POST['datos']; // Recibe los datos HTML del cliente
            // Agrega estilos básicos para el PDF
            $html = '<html><head><style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; text-align: center; margin-bottom: 20px; }
            p { font-size: 14px; margin-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .informe__bloque { border-bottom: 1px solid #ccc; padding: 10px; display: flex; align-items: center; margin-bottom: 5px; }
            .informe__label { font-weight: bold; margin-right: 10px; width: 120px; }
            .informe__valorDeuda { font-weight: bold; color: red; }
            /* Puedes agregar más estilos según tu estructura HTML */
        </style></head><body>' . $html . '</body></html>';
            $dompdf->loadHtml($html); // Carga el HTML en Dompdf
            $dompdf->render(); // Renderiza el HTML a PDF
            $dompdf->stream('Informe_deudores_TienVi'); // Envía el PDF al navegador
        }
    }
}
