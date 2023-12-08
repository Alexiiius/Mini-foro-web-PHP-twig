<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";

class UsuarioController extends Controller{

    const MENSAJE = [null, "Contraseña o correo no correcto.", "Usuario registrado correctamente.", "Error al registrar usuario."];
    const MENSAJE2 = [null];

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

        if ($idMensaje !== 3 && $idMensaje !== 0) {
            $idMensaje = 0;
        }
        
        $this->render("usuario/registro.php.twig", ["mensaje" => self::MENSAJE[$idMensaje] ]);
    }

    //Comprueba el token y si los campos estan vacios. Despues registra al usuario en la base de datos o redirige al registro con un mensaje de error
    public function registro(){
        if ( empty($_POST["email"]) || empty($_POST["nombre"]) || empty($_POST["pass"])){
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


}














?>