<?php

//Clase padre para todos los controladores.
//Abstracta dado que no se instanciara y unicamente dara herencia del constructor y
//metodo render para dibujar las vistas

//Importo libreria de twig
require_once "vendor/autoload.php";
require_once "librerias/twigExtension.php";

abstract class Controller {


    //atributo para la instancia twig
    private $twig;

    public function __construct(){

        //Indicale a twig donde reocger las vistas
        $x = new \Twig\Loader\FilesystemLoader("vistas") ;
        //Instancia twig con dicha configuracion y guardalo en el atributo
        $this->twig = new \Twig\Environment($x) ;
        $this->twig->addExtension(new TwigExtensions);
    }

    //Llama el metodo render de la instancia twig y pasale 
    //la vista y los datos a dibujar en forma de array vacio si no se envian datos
    public function render(string $vista, array $datos = []) {
        echo $this->twig->render($vista, $datos) ;
    }

    //Metodo para redireccionar a otras rutas desde el propio controlador.
    public function redireccion(string $ruta){
        header("Location: $ruta");
    }

    //Conjunto de uso de ambas funciones de comprobacion de sesion.
    public function sessionUpdate(){
        $this->checkOff();
        $this->checkOn();
    }

    //Comprobar si el usuario ha iniciado sesion. Si no es asi, destruye la sesion y redirige al login.
    public function checkOff(){
        if (!isset($_SESSION["usuario"])) {
            session_destroy();
            session_start();
            $this->redireccion("login");
        }
    }

    //Comprueba si la sesion no ha expirado y la actualiza. Si ha expirado, destruye la sesion y redirige al login.
    public function checkOn(){
        if (isset($_SESSION["inicio"]) && isset($_SESSION["expira"])) {
            if ($_SESSION["expira"] > time()) {
                $_SESSION["inicio"] = time();
                $_SESSION["expira"] = $_SESSION["inicio"] + (5 * 60);  //5 minutos para expirar sesion
                return true;
            }else{
                session_destroy();
                $this->redireccion("login");
            }
        }else{
            session_destroy();
            $this->redireccion("login");
        }
    }

}




?>