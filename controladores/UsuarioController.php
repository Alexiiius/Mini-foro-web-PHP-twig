<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";

class UsuarioController extends Controller{

    //Genero un token nuevo, guardo en session y rederizo la vista pasando el token
    public function renderLogin(string $mensaje = null){
        $token = Token::tokenizer()->getToken();
        $_SESSION["token"] = $token;
        $this->render("usuario/login.php.twig", ["token" => $token, "mensaje" => $mensaje]);
    }

    //Comprueba el token y si existe el usuario en la base de datos lo guarda en session y crea el tiempo de expiracion
    public function login(){
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["email"]) || empty($_POST["pass"])){
            $this->redireccion("login");
        }else{
            $usuario = Usuario::loginUsuario($_POST["email"], $_POST["pass"]);
            if (is_null($usuario)) {
                $this->redireccion("login");
                //TODO add error correo o contraseña no correcto
                $_SESSION['errorMensaje'] = "Contraseña o correo no correcto.";
            }
            $_SESSION["inicio"]  = time();
            $_SESSION["expira"]  = $_SESSION["inicio"] + (5 * 60);  //5 minutos para expirar sesion
            $_SESSION["usuario"] = $usuario;
            var_dump($usuario);
        }
    }

    //Renderizo la vista de registro
    public function renderRegistro(string $mensaje = null){
        $this->render("usuario/registro.php.twig", ["mensaje" => $mensaje]);
    }

    public function registro(){
        if ( empty($_POST["email"]) || empty($_POST["nombre"]) || empty($_POST["pass"])){
            $this->redireccion("registro");
        }

        $registro = Usuario::registrarUsuario($_POST["nombre"], $_POST["email"], $_POST["pass"]);
        if ($registro > 0) {
            $this->renderLogin("Usuario registrado correctamente.");
        }else{
            $this->renderRegistro("Error al registrar usuario.");
        }
    }


}














?>