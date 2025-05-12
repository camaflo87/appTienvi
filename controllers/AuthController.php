<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Personas;
use Model\Usuarios;

class AuthController
{
    public static function login(Router $router)
    {
        $alertas = [];
        $login_email = new Personas();;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login_email = new Personas($_POST);
            $login_password = new Usuarios($_POST);

            $alertas = $login_email->validarLogin();
            $alertas = $login_password->verificarPassword();

            if (empty($alertas)) {
                // Verificar que la persona exista
                $persona = Personas::where('email', $login_email->email);

                if (!$persona) {
                    Personas::setAlerta('error', 'El Usuario No Existe');
                } else {
                    // Verificar que el usuario exista
                    $usuario = Usuarios::find($persona[0]->id);

                    if (!$usuario) {
                        Personas::setAlerta('error', 'El Usuario No Existe');
                    } else {

                        if (password_verify($login_password->password, $usuario->password)) {
                            // Iniciar la sesión
                            session_start();
                            $_SESSION['id'] = $persona[0]->id;
                            $_SESSION['nombre'] = $persona[0]->nombre . " " . $persona[0]->apellido;
                            $_SESSION['email'] = $persona[0]->email;
                            $_SESSION['perfil'] = $usuario->perfil;

                            header('Location: /administracion');
                        } else {
                            Personas::setAlerta('error', 'Password Incorrecto');
                        }
                    }
                }
            }
        }

        $alertas = Personas::getAlertas();

        // Render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'admin' => 'Volver',
            'link' => '/',
            'login' => $login_email,
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $_SESSION = [];
            header('Location: /');
        }
    }

    public static function registroPersona(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        $alertas = [];
        $persona = new Personas;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $persona->sincronizar($_POST);

            // Convertir los datos a minuscula
            $persona->nombre = strtoupper($persona->nombre);
            $persona->apellido = strtoupper($persona->apellido);
            $persona->email = strtolower($persona->email);

            $alertas = $persona->validar_cuenta();
            $alertas = $persona->validarEmail();

            if (empty($alertas)) {
                $existeUsuario = Personas::where('email', $persona->email);

                if ($existeUsuario) {
                    Personas::setAlerta('error', 'El Usuario ya esta registrado con este email');
                    $alertas = Personas::getAlertas();
                } else {

                    // Crear un nuevo usuario
                    $resultado =  $persona->guardar();

                    if ($resultado) {
                        header('Location: /mensaje?var=exito&mens=El Cliente se creo con exito');
                    }
                }
            }
        }

        // Render a la vista
        $router->render('auth/registro', [
            'titulo' => 'Crear Persona',
            'alertas' => $alertas,
            'persona' => $persona
        ]);
    }

    public static function modificar_usuario(Router $router)
    {
        if (!is_auth()) {
            header('Location: /');
        }

        if ($_SESSION['perfil'] !== '2') {
            $_SESSION = [];
            header('Location: /');
        }

        $alertas = [];

        if (!empty($_GET['id'])) {

            $id = $_GET['id'];

            $persona = Personas::find($id);
            $usuario = Usuarios::find($id);

            if (empty($usuario)) {
                $usuario = new Usuarios();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $persona = new Personas($_POST);
            $usuario = new Usuarios($_POST);

            $alertas = $persona->validar_cuenta();
            $alertas = $persona->validarEmail();

            if (empty($alertas)) {
                // Valida si el perfil es 0
                if ($usuario->perfil === '0') {

                    if (empty($alertas)) {
                        $resultado = $persona->actualizar();
                        $usuario->eliminar();

                        if ($resultado) {
                            header('Location: /mensaje?var=exito&mens=Usuario actualizado exitosamente');
                        }
                    }
                } else { //Valida si el usuario existe y una nueva contraseña
                    $existe = $usuario->find($usuario->id);

                    if ($existe) {
                        // Validar cambio contraseña
                        if (empty($usuario->password) && empty($usuario->password_respaldo)) {
                            $resultado = $persona->actualizar();
                            $recupera_user = Usuarios::find($usuario->id);
                            $usuario->password = $recupera_user->password;

                            $resultado = $usuario->guardar();
                            if ($resultado) {
                                header('Location: /mensaje?var=exito&mens=Usuario actualizado exitosamente');
                            }
                        } else {
                            $alertas = $usuario->nuevo_password();

                            if (empty($alertas)) {
                                $usuario->hashPassword();
                                $resultado = $persona->actualizar();
                                $resultado = $usuario->guardar();
                                if ($resultado) {
                                    header('Location: /mensaje?var=exito&mens=Usuario actualizado exitosamente');
                                }
                            }
                        }
                        // Valida un nuevo usuario
                    } else {
                        $alertas = $usuario->nuevo_password();

                        if (empty($alertas)) {
                            $usuario->hashPassword();
                            $resultado = $persona->actualizar();
                            $resultado = $usuario->agregarUsuario($usuario->id, $usuario->password, $usuario->perfil);

                            if ($resultado) {
                                header('Location: /mensaje?var=exito&mens=Usuario actualizado exitosamente');
                            }
                        }
                    }
                }
            } // Cierra primera alerta         

        } // Cierra llave Post

        // Muestra la vista
        $router->render('auth/modificar', [
            'titulo' => 'Modificar Usuario',
            'alertas' => $alertas,
            'persona' => $persona,
            'usuario' => $usuario
        ]);
    }

    public static function reestablecer(Router $router)
    {

        $token = s($_GET['token']);

        $token_valido = true;

        if (!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo');
            $token_valido = false;
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();

                // Eliminar el Token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redireccionar
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'token_valido' => $token_valido
        ]);
    }

    public static function mensaje(Router $router)
    {
        $alertas = [];
        $var = s($_GET['var']);
        $mensaje = s($_GET['mens']);

        $alertas[$var][] = $mensaje;

        $router->render('auth/mensaje', [
            'titulo' => 'Area de Mensajes',
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) header('Location: /');

        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);

            // Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }



        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta DevWebcamp',
            'alertas' => Usuario::getAlertas()
        ]);
    }
}
