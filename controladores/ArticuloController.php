<?php


require_once "Controller.php";
require_once "modelos/Articulo.php";
require_once "librerias/Token.php";


class ArticuloController extends Controller{


    //Renderizo la vista de registro cargando todos los registros de la DB
    //Se actualiza la sesion  agregando 5 minutos mas y se comprueba si existe el usuario en la sesion
    //De no existir se redirige a la pagina de login
    public function renderHome(){

        if (!$this->sessionUpdate()) {
            $this->redireccion("login");
        }

        if (isset($_SESSION["usuario"])) {
            $usuario = unserialize($_SESSION["usuario"]);
            $articulos = Articulo::getArticulos();
            $this->render("articulo/home.php.twig", ["articulos" => $articulos, "usuario" => $usuario]);
        }else{  
            $this->redireccion("login");
        }
    }

}

?>