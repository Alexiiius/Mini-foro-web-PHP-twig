<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";
require_once "librerias/validacionDatos.php";

class UsuarioController extends Controller{

    const MENSAJE = [null, "Contraseña o correo no correcto.", "Usuario registrado correctamente.", "Error al registrar usuario."];
    const MENSAJE2 = [null, "Dato actualizado correctamente.", "Error al actualizar dato."];

    //Genero un token nuevo, guardo en session y rederizo la vista pasando el token
    //Mediante GET se esta pasando el numero del mensaje que contiene el array MENSAJE para mostrar el mensaje correspondiente
    //Se comprueba si hay una session ya iniciada y se redirige a la pagina de inicio.
    public function renderLogin(){

        if (isset($_SESSION["usuario"])) {
            $this->redireccion("home");
        }

        $token = Token::tokenizer()->getToken();
        $_SESSION["token"] = $token;
        $idMensaje = $_GET["mensaje"]??0;
        //En caso de que el mensaje no este entre 0 y 2 se pone el mensaje por defecto
        if ($idMensaje < 0 || $idMensaje > 2) {
            $idMensaje = 0;
        }
        $this->render("usuario/login.php.twig", ["token" => $token, "mensaje" => self::MENSAJE[$idMensaje] ]);
    }

    public function renderPerfil(){
        $this->sessionUpdate();

        if (!isset($_SESSION["usuario"])) {
            $this->redireccion("login");
        }

        $token = Token::tokenizer()->getToken();
        $_SESSION["token"] = $token;
        $idMensaje = $_GET["mensaje"]??0;

        $this->render("usuario/perfil.php.twig", ["token" => $token, "mensaje" => self::MENSAJE2[$idMensaje] ]);
    }

    //Comprueba el token y si existe el usuario en la base de datos
    //En caso afirmativo, se guarda el usuario recuperado en la sesion y se redirige a la pagina de inicio junto con la variable de sesion inicio y expira
    public function login(){
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["email"]) || empty($_POST["pass"])){
            $this->redireccion("login");
        }else{
            $usuario = Usuario::loginUsuario($_POST["email"], $_POST["pass"]);
            if (is_null($usuario)) {
                $this->redireccion("login?mensaje=1");
                //$this->renderLogin("Contraseña o correo no correcto.");
            }else{
                $_SESSION["inicio"]  = time();
                $_SESSION["expira"]  = $_SESSION["inicio"] + (5 * 60);  //5 minutos para expirar sesion
                $_SESSION["usuario"] = $usuario; //Guardo el objeto usuario en la sesion
                $this->redireccion("home");
            }
        }
    }

    //Renderizo la vista de registro
    //En caso de que el mensaje no este entre 0 y 3 se pone el mensaje por defecto
    public function renderRegistro(){

        if (isset($_SESSION["usuario"])) {
            $this->redireccion("home");
        }

        $idMensaje = $_GET["mensaje"]??0;

        if ($idMensaje != 3 && $idMensaje !== 0) {
            $idMensaje = 0;
        }
        
        $this->render("usuario/registro.php.twig", ["mensaje" => self::MENSAJE[$idMensaje] ]);
    }

    //Comprueba el token y si los campos estan vacios. Despues registra al usuario en la base de datos o redirige al registro con un mensaje de error
    public function registro(){
        if ( empty($_POST["email"]) || empty($_POST["nombre"]) || empty($_POST["pass"]) || !validarEmail($_POST["email"]) || !validarNombre($_POST["nombre"])){
            $this->redireccion("registro");
        }

        $registro = Usuario::registrarUsuario($_POST["nombre"], $_POST["email"], $_POST["pass"]);
        if ($registro > 0) {
            //$this->renderLogin("Usuario registrado correctamente.");
            $this->redireccion("login?mensaje=2");
        }else{
            //$this->renderRegistro("Error al registrar usuario.");
            $this->redireccion("registro?mensaje=3");
        }
    }


    //Cerrar sesion y redirigir al login
    public function cerrarSesion(){
        session_unset();
        session_destroy();
        $this->redireccion("login");
    }

    //Edita el dato del usuario en la base de datos y redirige a la pagina de perfil
    public function editarDato(){
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_GET["actua"]) || empty($_POST["tipo"])){
            $this->redireccion("perfil");
        }else{

            //Compruebo que el usuario existe en la base de datos y recojos sus datos para comprobar la contraseña
            $salida = Usuario::getUsuario($_SESSION["usuario"]->idUsuario);
            if (is_null($salida)) {
                //caso imposible, usuario no encontrado por su ID
                $this->redireccion("perfil?mensaje=2");
            }

            switch ($_POST["tipo"]) {
                case 'nombre':
                    if (validarNombre($_POST["nombre"])) {
                        $resultado = Usuario::editarNombre($_SESSION["usuario"]->idUsuario, $_POST["nombre"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                case 'apellido':
                    if (validarApellido($_POST["apellido"])) {
                        $resultado = Usuario::editarApellido($_SESSION["usuario"]->idUsuario, $_POST["apellido"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                case 'telefono':
                    if (validarTelefono($_POST["telefono"])) {
                        $resultado = Usuario::editarTelefono($_SESSION["usuario"]->idUsuario, $_POST["telefono"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                case 'email':
                    if (validarEmail($_POST["email"])) {
                        $resultado = Usuario::editarEmail($_SESSION["usuario"]->idUsuario, $_POST["email"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                case 'pw':
                    if (validarPassword($_POST["pw"]) && password_verify($_POST["pw2"], $salida['contraseña'])) {
                        $resultado = Usuario::editarPass($_SESSION["usuario"]->idUsuario, $_POST["pw"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                case 'dni':
                    if (validarDNI($_POST["dni"])) {
                        $resultado = Usuario::editarDNI($_SESSION["usuario"]->idUsuario, $_POST["dni"]);
                    } else {
                        $resultado = 0;
                    }
                    break;
                default:
                    $resultado = 0;
                    break;
            }
        }

        //En caso de error, redireccion con mensaje error 2. En caso contrario se actualiza la sesion y se redirige con mensaje 1
        if ($resultado > 0) {

            $salida = Usuario::getUsuario($_SESSION["usuario"]->idUsuario);
            if (is_null($salida)) {
                //caso imposible, usuario no encontrado por su ID
                $this->redireccion("perfil?mensaje=2");
            }

            $usuario = new Usuario();
            $usuario->idUsuario = $salida['idUsuario'];
            $usuario->email = $salida['email'];
            $usuario->DNI = $salida['DNI'];
            $usuario->pw = $salida['contraseña'];
            $usuario->nombre = $salida['nombre'];
            $usuario->apellido = $salida['apellido'];
            $usuario->telefono = $salida['telefono'];

            $_SESSION["usuario"] = $usuario;
            $this->redireccion("perfil?mensaje=1");
            
        }else{
            $this->redireccion("perfil?mensaje=2");
        }

    }



}

?>