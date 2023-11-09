<?php

require_once "Controller.php";
require_once "../modelos/Usuario.php";
require_once "../librerias/Token.php";

class UsuarioController extends Controller{


    public function renderLogin(){


        $token = Token::tokenizer();


    }



}














?>