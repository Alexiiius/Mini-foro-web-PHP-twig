<?php

require_once "librerias/Conexion.php";

class Articulo {

    public int $idArticulo;
    public int $idAutor;
    public string $titulo;
    public string $contenido;
    public string $fecha;


    //No se esta usando actualmente ni el constructor ni sus atributos, no se isntancia ningun objeto de esta clase
    public function __construct(){}


    //no se esta usndo actualmente, obtiene un articulo segun su id
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
                                GROUP BY Articulos.idArticulo
                                ORDER BY fecha_publicacion DESC");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    //Devuelve un array con todos los articulos pertenecientes a un autor en concreto
    public static function getArticulosAutor(int $idAutor){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT Articulos.*, Usuarios.nombre, ROUND(AVG(Ratings.puntuaje), 1) as rating 
                                FROM Articulos 
                                JOIN Usuarios ON Articulos.id_autor = Usuarios.idUsuario 
                                LEFT JOIN Ratings ON Articulos.idArticulo = Ratings.id_articulo 
                                WHERE id_autor = :idAutor
                                GROUP BY Articulos.idArticulo
                                ORDER BY fecha_publicacion DESC;");
        $stmt->bindValue(':idAutor', $idAutor, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    //Elimina un articulo concreto perteneciente a un usuario concreto
    public static function eliminar(int $idArticulo, int $idUsuario){
        $pdo = Conexion::getConnection()->getPdo();
    
        //primero elimina los ratings asociados al articulo
        $stmt = $pdo->prepare("DELETE FROM Ratings WHERE id_articulo = :idArticulo");
        $stmt->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            unset($pdo);
            return $resultado = 0;
        }
    
        //luego elimina el articulo
        $stmt = $pdo->prepare("DELETE FROM Articulos WHERE idArticulo = :idArticulo AND id_autor = :idUsuario");
        $stmt->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
    
        unset($pdo);
        return $resultado;
    }

    //Registra el rating de un articulo en la DB junto con el usuario que lo puntuo
    public static function puntuar(int $idArticulo, int $idUsuario, string $puntuaje){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO Ratings (id_articulo, id_usuario, puntuaje) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $idArticulo, PDO::PARAM_INT);
        $stmt->bindValue(2, $idUsuario, PDO::PARAM_INT);
        $puntuajeFloat = floatval($puntuaje);
        $stmt->bindValue(3, $puntuajeFloat);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }

    //Crea un articulo en la DB
    public static function crear(string $titulo, string $contenido, int $idUsuario){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO Articulos (titulo, contenido, id_autor) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
        $stmt->bindParam(2, $contenido, PDO::PARAM_STR);
        $stmt->bindParam(3, $idUsuario, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }

    //Devuelve el rating de un articulo en la DB junto con el usuario que lo puntuo
    public static function yaPuntuo(int $idArticulo, int $idUsuario){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Ratings WHERE id_articulo = :idArticulo AND id_usuario = :idUsuario");
        $stmt->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }

    //Actualiza el rating de un articulo en la DB junto con el usuario que lo puntuo
    public static function actualizarPuntuaje(int $idArticulo, int $idUsuario, string $puntuaje){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Ratings SET puntuaje = :puntuaje WHERE id_articulo = :idArticulo AND id_usuario = :idUsuario");
        $stmt->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $puntuajeFloat = floatval($puntuaje);
        $stmt->bindValue(':puntuaje', $puntuajeFloat);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }

}

?>