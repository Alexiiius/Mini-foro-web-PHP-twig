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

        $articulo = new Articulo();
        $articulo->idArticulo = $resultado['idArticulo'];
        $articulo->idAutor = $resultado['idAutor'];
        $articulo->titulo = $resultado['titulo'];
        $articulo->contenido = $resultado['contenido'];
        $articulo->fecha = $resultado['fecha'];

        unset($pdo);
        return $articulo;
    }

    //Devuelve un array con todos los articulos de la DB y sus respectivos autores 
    //y el rating promedio redondeado a 1 decimal
    public static function getArticulos(){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT Articulos.*, Usuarios.nombre, ROUND(AVG(Ratings.puntuaje), 1) as rating 
                                FROM Articulos 
                                JOIN Usuarios ON Articulos.id_autor = Usuarios.idUsuario 
                                LEFT JOIN Ratings ON Articulos.idArticulo = Ratings.id_articulo 
                                GROUP BY Articulos.idArticulo");
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


    //Registra el rating de un articulo en la DB junto con el usuario que lo puntuo
    public static function puntuar(int $idArticulo, int $idUsuario, int $puntuaje){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO Ratings (id_articulo, id_usuario, puntuaje) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $idArticulo, PDO::PARAM_INT);
        $stmt->bindParam(2, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(3, $puntuaje, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->rowCount();
        unset($pdo);
        return $resultado;
    }

}

?>