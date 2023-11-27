<?php

    // importamos la librería TWIG
    require_once "vendor/autoload.php" ;

    //Clase que extiende de la clase AbstractExtension de Twig para poder usarla en las vistas y pasarle variables globales como la sesion
    //Accesible desde la vista con _session.variable
    class TwigExtensions extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface {


        public function getGlobals(): array {
            return [
                "_session" => $_SESSION,                
            ] ;
         }

    }

?>