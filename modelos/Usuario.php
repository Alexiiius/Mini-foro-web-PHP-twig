<?php

require_once "librerias/Conexion.php";

class Usuario {

    public int $idUsuario;
    public string $email;
    public string $DNI;
    public string $pw; 
    public string $nombre;
    public string $apellido;
    public string $telefono;

    public function __construct(){}

    //haz la consulta y devuelve resultado
    public static function getUsuario(int $i){
        //TODO
    }

    //Consulta a la DB y hasheo de pw
    public static function loginUsuario(string $email, string $pw){
        //TODO
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE email = :correo AND contraseña = :pw");
        $stmt->bindValue(':correo', $email, PDO::PARAM_STR);
        $pw = password_hash($pw, PASSWORD_ARGON2ID);
        $stmt->bindValue(':pw', $pw, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $usuario = new Usuario();
            $usuario->idUsuario = $resultado['idUsuario'];
            $usuario->email = $resultado['email'];
            $usuario->DNI = $resultado['DNI'];
            $usuario->pw = $resultado['contraseña'];
            $usuario->nombre = $resultado['nombre'];
            $usuario->apellido = $resultado['apellido'];
            $usuario->telefono = $resultado['telefono'];
            unset($pdo);
            return $usuario;
        } else {
            unset($pdo);
            return null;
        }

    }

    public static function registrarUsuario(){
        //TODO
    }


}