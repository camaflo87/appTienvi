<?php

namespace Model;

class Usuarios extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'password', 'perfil'];

    public $id;
    public $password;
    public $password_respaldo;
    public $perfil;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password_respaldo = $args['password_respaldo'] ?? '';
        $this->perfil = $args['perfil'] ?? '0';
    }

    // Validar el Login de Usuarios
    public function validarUsuario()
    {
        if (!$this->id) {
            self::$alertas['error'][] = 'El usuario no registra.';
        }
        return self::$alertas;
    }

    // Validar el datos de Personas
    public function verificarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }

        return self::$alertas;
    }

    public function nuevo_password(): array
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if (!$this->password_respaldo) {
            self::$alertas['error'][] = 'El Password de confirmación no puede ir vacio';
        }
        if (strlen($this->password) < 10 && strlen($this->password_respaldo) < 10) {
            self::$alertas['error'][] = 'El Password debe contener al menos 10 caracteres';
        }

        if ($this->password !== $this->password_respaldo) {
            self::$alertas['error'][] = 'El Password de confirmación no coincide';
        }
        return self::$alertas;
    }

    // // Comprobar el password
    // public function comprobar_password(): bool
    // {
    //     return password_verify($this->password_actual, $this->password);
    // }

    // Hashea el password
    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken(): void
    {
        $this->token = uniqid();
    }
}
