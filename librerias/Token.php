<?php

// Clase singelton para el generador de Tokens

abstract class Token{


    private static ?Token $instancia = null;
    private string $token;

    private function __construct(){

        //Genero un token aleatorio con la marca de tiempo
        //Sumado a un numero aleatorio y la encripto para enrevesarlo aun mas
        $this->token = password_hash(uniqid(mt_rand()), PASSWORD_ARGON2ID);
    }
    private function __clone(){}





    public static function Tokenizer():Token{

        if (self::$instancia==null) {
            self::$instancia = new Token;
        }
        return self::$instancia;
    }

    public function getToken(): string {
        return $this->token;
    }

    //Para obtener el token se llama a Token::Tokenizer()->getToken();
    //Por norma general interesara guardarlo en el session pero eso excede a esta clase


}





?>