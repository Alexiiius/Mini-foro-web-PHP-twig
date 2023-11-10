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

/*    public static function getUsuario(int $i){
        return Conexion::getConexion();
    }
*/




}