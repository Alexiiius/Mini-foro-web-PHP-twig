<?php

require_once "Controller.php";
require_once "../modelos/Usuario.php";
require_once "../librerias/Token.php";

class UsuarioController extends Controller{


    public function renderLogin(){

        //Genero un token aleatorio con la marca de tiempo
        //Sumado a un numero aleatorio y la encripto luego para enrevesarlo aun mas
        $token = Token::tokenizer();


    }



}














?>