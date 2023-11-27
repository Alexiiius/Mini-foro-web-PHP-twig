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

}




?>