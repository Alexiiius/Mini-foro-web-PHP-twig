<?php

require_once "librerias/Conexion.php";

class Usuario {

    public int $idUsuario;
    public string $email;
    public ?string $DNI;
    public string $pw; 
    public string $nombre;
    public ?string $apellido;
    public ?string $telefono;

    public function __construct(){}

    //haz la consulta y devuelve resultado
    public static function getUsuario(int $id){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':idUsuario', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    public  static function getUsuarioByEmail(string $email){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE email = :correo");
        $stmt->bindValue(':correo', $email, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        unset($pdo);
        return $resultado;
    }

    //Consulto a la DB si existe el correo del usuario y si su contraseña es la misma devuelve un objeto usuario con sus datos. 
    //De otro modo cierra la conexion y devuelve null.
    //Los datos del usuario obtenido se guardan en un objeto usuario y se devuelve. Puede haber datos nulos tales como DNI, apellido y telefono.
    public static function loginUsuario(string $email, string $pw){

        $resultado = self::getUsuarioByEmail($email);

        if (!$resultado) {
            return null;
        }

        if (password_verify($pw, $resultado['contraseña'])) {
            $usuario = new Usuario();
            $usuario->idUsuario = $resultado['idUsuario'];
            $usuario->email = $resultado['email'];
            $usuario->DNI = $resultado['DNI'];
            $usuario->pw = $resultado['contraseña'];
            $usuario->nombre = $resultado['nombre'];
            $usuario->apellido = $resultado['apellido'];
            $usuario->telefono = $resultado['telefono'];
            return $usuario;
        } else {
            return null;
        }

    }

    //Inserta un nuevo usuario en la base de datos y devuelve el numero de filas afectadas
    //En caso de error devuelve 0. Este error contempla el correo repetido.
    //La contraseña se encripta con password_hash usando el algoritmo PASSWORD_ARGON2ID
    //La comprobacion de la contraseña se hace con password_verify en el metodo loginUsuario
    public static function registrarUsuario(string $nombre, string $email, string $pw){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO Usuarios (email, nombre, contraseña) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $pw2 = (password_hash($pw, PASSWORD_ARGON2ID));
        $stmt->bindParam(3, $pw2, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }

    public static function editarNombre(int $idUsuario, string $nombre){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET nombre = :nombre WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
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

    public static function editarApellido(int $idUsuario, string $apellido){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET apellido = :apellido WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
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

    public static function editarTelefono(int $idUsuario, string $telefono){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET telefono = :telefono WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':telefono', $telefono, PDO::PARAM_STR);
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

    public static function editarEmail(int $idUsuario, string $email){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET email = :email WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
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

    public static function editarDNI(int $idUsuario, string $DNI){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET DNI = :DNI WHERE idUsuario = :idUsuario");
        $stmt->bindValue(':DNI', $DNI, PDO::PARAM_STR);
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

    public static function editarPass(int $idUsuario, string $pw){
        $pdo = Conexion::getConnection()->getPdo();
        $stmt = $pdo->prepare("UPDATE Usuarios SET contraseña = :pw WHERE idUsuario = :idUsuario");
        $pw2 = (password_hash($pw, PASSWORD_ARGON2ID));
        $stmt->bindValue(':pw', $pw2, PDO::PARAM_STR);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $resultado = $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e;
            $resultado = 0;
        }
        unset($pdo);
        return $resultado;
    }


}

?>