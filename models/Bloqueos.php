<?php

namespace Model;

class Bloqueos extends ActiveRecord
{
    protected static $tabla = 'bloqueos';
    protected static $columnasDB = ['id', 'fecha_bloqueo', 'saldo'];

    public $id;
    public $fecha_bloqueo;
    public $saldo;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha_bloqueo = $args['fecha_bloqueo'] ?? '';
        $this->saldo = $args['saldo'] ?? '';
    }
}
