<?php


require_once "Controller.php";
require_once "modelos/Articulo.php";
require_once "librerias/Token.php";

class ArticuloController extends Controller{

    const MENSAJE = [null, "Puntuaje enviado correctamente.", "Error al puntuar.", "Error al crear articulo.", "Articulo creado correctamente.", "Articulo eliminado correctamente.", "Error al eliminar articulo."];

    //Renderizo la vista de registro cargando todos los registros de la DB
    //Se actualiza la sesion  agregando 5 minutos mas y se comprueba si existe el usuario en la sesion
    //De no existir se redirige a la pagina de login
    public function renderHome(){
        $this->sessionUpdate();
        if (isset($_SESSION["usuario"])) {
            $idMensaje = $_GET["mensaje"]??0;

            if ($idMensaje < 0 || $idMensaje > 6) {
                $idMensaje = 0;
            }

            $token = Token::tokenizer()->getToken();
            $_SESSION["token"] = $token;
            $articulos = Articulo::getArticulos();
            $this->render("articulo/home.php.twig", ["articulos" => $articulos, "token" => $token, "mensaje" => self::MENSAJE[$idMensaje]]);
        }else{  
            $this->redireccion("login");
        }
    }

    //Renderiza la vista de los articulos del usuario logueado de la pagina "Mis articulos"
    public function renderPersonal(){
        $this->sessionUpdate();
        if (isset($_SESSION["usuario"])) {
            $idMensaje = $_GET["mensaje"]??0;

            if ($idMensaje < 0 || $idMensaje > 4) {
                $idMensaje = 0;
            }

            $token = Token::tokenizer()->getToken();
            $_SESSION["token"] = $token;

            $id = $_GET['id'] ?? null;

            if ($id === null) {
                $articulos = Articulo::getArticulosAutor($_SESSION["usuario"]->idUsuario);
            }else{
                $articulos = Articulo::getArticulosAutor($id);
            }

            
            $this->render("articulo/personal.php.twig", ["articulos" => $articulos, "token" => $token, "mensaje" => self::MENSAJE[$idMensaje]]);
        }else{  
            $this->redireccion("login");
        }
    }
    
    //Se crea un articulo en la DB
    public function crearArticulo(){
        $this->sessionUpdate();
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["titulo"]) || empty($_POST["contenido"]) || empty($_SESSION["usuario"]->idUsuario)){
            $this->redireccion("login");
        }else{
            $resultado = Articulo::crear($_POST["titulo"], $_POST["contenido"], $_SESSION["usuario"]->idUsuario);
            if ($resultado) {
                $this->redireccion("home?mensaje=4");
            }else{
                $this->redireccion("home?mensaje=3");
            }
        }
    }


    //Se añade el rating del articulo a la DB
    //Si el usuario ya puntuo el mismo articulo, se hace un update en vez de un insert.
    public function puntuarArticulo(){
        $this->sessionUpdate();

        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["nota"]) || empty($_POST["id_articulo"]) || empty($_POST["id_usuario"])){
            $this->redireccion("login");
            return;
        }
 
        if (Articulo::yaPuntuo($_POST["id_articulo"], $_POST["id_usuario"])) {
            $resultado = Articulo::actualizarPuntuaje($_POST["id_articulo"], $_POST["id_usuario"], $_POST["nota"]);
        } else {
            $resultado = Articulo::puntuar($_POST["id_articulo"], $_POST["id_usuario"], $_POST["nota"]);
        }
    
        if ($resultado) {
            $this->redireccion("home?mensaje=1");
        } else {
            $this->redireccion("home?mensaje=2");
        }
    }

    public function eleminarArticulo(){
        $this->sessionUpdate();
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["id_articulo"]) || empty($_SESSION["usuario"]->idUsuario)){
            $this->redireccion("login");
        }else{
            $resultado = Articulo::eliminar($_POST["id_articulo"], $_SESSION["usuario"]->idUsuario);
            if ($resultado) {
                $this->redireccion("home?mensaje=5");
            }else{
                $this->redireccion("home?mensaje=6");
            }
        }
    }

}

?>