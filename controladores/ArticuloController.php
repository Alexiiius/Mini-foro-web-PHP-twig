<?php


require_once "Controller.php";
require_once "modelos/Articulo.php";
require_once "librerias/Token.php";

class ArticuloController extends Controller{

    const MENSAJE = [null, "Puntuaje enviado correctamente.", "Error al puntuar."];

    //Renderizo la vista de registro cargando todos los registros de la DB
    //Se actualiza la sesion  agregando 5 minutos mas y se comprueba si existe el usuario en la sesion
    //De no existir se redirige a la pagina de login
    public function renderHome(){

        $this->sessionUpdate();
        if (isset($_SESSION["usuario"])) {
            $idMensaje = $_GET["mensaje"]??0;

            if ($idMensaje < 0 || $idMensaje > 2) {
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

    //Se añade el rating del articulo a la DB
    public function puntuarArticulo(){

        $this->sessionUpdate();
        if ($_POST["_csrf"] != $_SESSION["token"] || empty($_POST["nota"]) || empty($_POST["id_articulo"]) || empty($_POST["id_usuario"])){
            $this->redireccion("login");
        }else{
            $resultado = Articulo::puntuar($_POST["id_articulo"], $_POST["id_usuario"], $_POST["nota"]);
            if ($resultado) {
                $this->redireccion("home?mensaje=1");
            }else{
                $this->redireccion("home?mensaje=2");
            }
        }
    }

}

?>