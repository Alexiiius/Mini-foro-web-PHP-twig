<?php

    // importamos la librería TWIG
    require_once "vendor/autoload.php" ;

    class TwigExtensions extends \Twig\Extension\AbstractExtension
                         implements \Twig\Extension\GlobalsInterface {


        public function getGlobals(): array {
            return [
                "_session" => $_SESSION,                
            ] ;
         }

    }

?>