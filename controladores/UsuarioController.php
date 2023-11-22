<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";

class UsuarioController extends Controller{

    //Genero un token nuevo, guardo en session y rederizo la vista pasando el token
    public function renderLogin(){
        $token = Token::tokenizer()->getToken();
        $_SESSION["token"] = $token;
        $this->render("usuario/login.php.twig", ["token" => $token]);
    }

    //Comprueba el token y si existe el usuario en la base de datos
    public function login(){
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["email"]) || empty($_POST["pass"])){
            redireccion("main");
        }else{
            $usuario = Usuario::loginUsuario($_POST["email"], $_POST["pass"]);
            if (is_null($usuario)) {
                redireccion("main");
                //TODO add error correo o contraseña no correcto
                $_SESSION['errorMensaje'] = "Contraseña o correo no correcto.";
            }
            $_SESSION["inicio"]  = time();
            echo "Usuario logueado";
        }
    }

    public function registrar(){
        //TODO
    }


}














?>