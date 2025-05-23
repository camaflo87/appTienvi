<?php

namespace Model;

class Personas extends ActiveRecord
{
    protected static $tabla = 'personas';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'movil'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $movil;
    public $perfil;
    public $deuda;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->movil = $args['movil'] ?? '';
    }

    // Validar el datos de Personas
    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        // if (!$this->password) {
        //     self::$alertas['error'][] = 'El Password no puede ir vacio';
        // }
        return self::$alertas;
    }

    public function formateado(): void
    {
        $this->nombre = strtoupper($this->nombre);
        $this->apellido = strtoupper($this->apellido);
        $this->email = strtolower($this->email);
    }

    // Validación para cuentas nuevas
    public function validar_cuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if (!$this->movil) {
            self::$alertas['error'][] = 'El Movil es Obligatorio';
        }
        return self::$alertas;
    }

    // Valida un email
    public function validarEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    // Valida el Password 
    // public function validarPassword()
    // {
    //     if (!$this->password) {
    //         self::$alertas['error'][] = 'El Password no puede ir vacio';
    //     }
    //     if (strlen($this->password) < 6) {
    //         self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
    //     }
    //     return self::$alertas;
    // }

    // public function nuevo_password(): array
    // {
    //     if (!$this->password_actual) {
    //         self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
    //     }
    //     if (!$this->password_nuevo) {
    //         self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
    //     }
    //     if (strlen($this->password_nuevo) < 6) {
    //         self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
    //     }
    //     return self::$alertas;
    // }

    // Comprobar el password
    // public function comprobar_password(): bool
    // {
    //     return password_verify($this->password_actual, $this->password);
    // }

    // // Hashea el password
    // public function hashPassword(): void
    // {
    //     $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    // }

    // // Generar un Token
    // public function crearToken(): void
    // {
    //     $this->token = uniqid();
    // }
}
