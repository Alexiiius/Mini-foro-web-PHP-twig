<?php

    //Clase singleton para la conexion a la DB

    const DIRECCION = "mysql";
    const USUARIO = "root";
    const PASSWORD = "";
    const NOMBRE_DB = "ProyectoServidor";

    class Conexion{

    private static ?Conexion $instancia = null;
    private $pdo;

    private function __construct(){

        try {
            
            //$uri = "mysql:host".HOST";dbname=".name";charset=UTF-8";
            $this->pdo = new PDO("mysql:host=".DIRECCION.";dbname=".NOMBRE_DB, USUARIO, PASSWORD);
            //Cambio el atributo de errores a excepciones para que no devuelva false y me diga concretamente el error si peta
            //en cualquier lugar mientras se use la conexion
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }

    //unset(pdo)
    public function __destruct() {
        $this->pdo = null;
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }

    public static function getConnection():Conexion {

        if (self::$instancia==null) {
            self::$instancia = new Conexion ;
        }
        return self::$instancia ;
    }

    

    }

?>