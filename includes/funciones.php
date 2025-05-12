<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

function recortarTexto($texto): string
{
    $posicionEspacio = strpos($texto, ' ');
    if ($posicionEspacio !== false) {
        return substr($texto, 0, $posicionEspacio) . " " . substr($texto, $posicionEspacio, 2) . ".";
    } else {
        // Si no se encuentra ningún espacio, devolvemos el texto completo
        return $texto;
    }
}


function is_auth(): bool
{
    session_start();
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}
