<?php

require_once "librerias/Conexion.php";

class Articulo {

    public int $idArticulo;
    public int $idAutor;
    public string $titulo;
    public string $contenido;
    public string $fecha;


    public function __construct(){}


    public static function getArticulo(int $i){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE idArticulo = :idArticulo");
        $stmt->bindValue(':idArticulo', $i, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    public static function getArticulos(){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Articulos");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    public static function getArticulosAutor(int $idAutor){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Articulos WHERE idAutor = :idAutor");
        $stmt->bindValue(':idAutor', $idAutor, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    public static function borrarArticulo(int $idArticulo){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("DELETE FROM Articulos WHERE idArticulo = :idArticulo");
        $stmt->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->rowCount();
        unset($pdo);
        return $resultado;
    }

    public static function registrarArticulo(int $idAutor, string $titulo, string $contenido){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO Articulos (idAutor, titulo, contenido) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $idAutor, PDO::PARAM_INT);
        $stmt->bindParam(2, $titulo, PDO::PARAM_STR);
        $stmt->bindParam(3, $contenido, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->rowCount();
        unset($pdo);
        return $resultado;
    }

}

?>