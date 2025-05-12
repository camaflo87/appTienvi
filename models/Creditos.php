<?php

namespace Model;

class Creditos extends ActiveRecord
{
    protected static $tabla = 'creditos';
    protected static $columnasDB = ['id', 'id_persona', 'fecha', 'credito'];

    public $id;
    public $id_persona;
    public $fecha;
    public $credito;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_persona = $args['id_persona'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->credito = $args['credito'] ?? '';
    }
}
