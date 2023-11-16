<?php

    //Clase singleton para la conexion a la DB

    const direccion = "mysql";
    const usuario = "root";
    const password = "";
    const nombreDB = "ProyectoServidor";

    class Conexion{

    private static ?Conexion $instancia = null;
    private $pdo;

    private function __construct(){

        try {
            
            $this->$pdo = new PDO("mysql:host=".direccion.";dbname=".nombre_db, usuario, password);
            //Cambio el atributo de errores a excepciones para que no devuelva false y me diga concretamente el error si peta
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }

    public function __destruct() {
        print "Destroying " . __CLASS__ . "\n";
    }

    public static function getConnection():Conexion {

        if (elf::$instancia==null) {
            self::$instancia = new Conexion ;
        }
        return self::$instancia ;
    }



    }

?>