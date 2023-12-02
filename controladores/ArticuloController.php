<?php


require_once "Controller.php";
require_once "modelos/Articulo.php";
require_once "librerias/Token.php";


class ArticuloController extends Controller{


    public function renderHome(){
        $this->sessionUpdate();


        if (condition) {
            # code...
        }

        if (isset($_SESSION["usuario"])) {
            $usuario = unserialize($_SESSION["usuario"]);
            $articulos = Articulo::getArticulos();
            $this->render("articulo/home.php.twig", ["articulos" => $articulos]);
        }else{  
            $this->redirect("login");
        }
    }

}

?>