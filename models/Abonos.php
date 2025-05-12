<?php

namespace Model;

class Abonos extends ActiveRecord
{
    protected static $tabla = 'abonos';
    protected static $columnasDB = ['id', 'fecha', 'id_persona', 'abono'];

    public $id;
    public $fecha;
    public $id_persona;
    public $abono;
    public $total;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->id_persona = $args['id_persona'] ?? '';
        $this->abono = $args['abono'] ?? '';
    }
}
