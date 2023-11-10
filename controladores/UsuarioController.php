<?php

require_once "Controller.php";
require_once "modelos/Usuario.php";
require_once "librerias/Token.php";

class UsuarioController extends Controller{


    public function renderLogin(){

        //Genero un token nuevo, guardo en session y pido la vista
        $token = Token::tokenizer()->getToken();
        $SESSION_["token"] = $token;

        $this->render("usuario/login.php.twig", ["token" => $token]);
    }



}














?>