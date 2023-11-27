<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";

class UsuarioController extends Controller{

    const MENSAJE = [null, "Contraseña o correo no correcto.", "Usuario registrado correctamente.", "Error al registrar usuario."];

    //Genero un token nuevo, guardo en session y rederizo la vista pasando el token
    public function renderLogin(){
        $token = Token::tokenizer()->getToken();
        $_SESSION["token"] = $token;
        $idMensaje = $_GET["mensaje"]??0;
        $this->render("usuario/login.php.twig", ["token" => $token, "mensaje" => self::MENSAJE[$idMensaje] ]);
    }

    //Comprueba el token y si existe el usuario en la base de datos lo guarda en session y crea el tiempo de expiracion
    public function login(){
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["email"]) || empty($_POST["pass"])){
            $this->redireccion("login");
        }else{
            $usuario = Usuario::loginUsuario($_POST["email"], $_POST["pass"]);
            if (is_null($usuario)) {
                $this->redireccion("login?mensaje=1");
                //$this->renderLogin("Contraseña o correo no correcto.");
            }
            $_SESSION["inicio"]  = time();
            $_SESSION["expira"]  = $_SESSION["inicio"] + (5 * 60);  //5 minutos para expirar sesion
            $_SESSION["usuario"] = $usuario;
        }
    }

    //Renderizo la vista de registro
    public function renderRegistro(){
        $idMensaje = $_GET["mensaje"]??0;
        $this->render("usuario/registro.php.twig", ["mensaje" => self::MENSAJE[$idMensaje] ]);
    }

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


}














?>