<?php

//  CONTROLADOR FRONTAL  //

//Inicion session para toda la web
session_start();

// Compruebo los metodos de envio y en ausencia de ellos redirigio al login
// Esto se encarga de recoger cualquier informacion de los formularios 
// que hayan direccionado hasta index

$f = $GET["funcion"]??$_POST["funcion"]??"renderLogin";         //Por defecto renderizamos el login siempre en ausencia de otra orden
$c = $GET["controlador"]??$_POST["controlador"]??"Usuario";

//Hago un string con el nombre del controlador concadenando texto que comparten todos
$controlador = "{$c}Controller";

//Hago la ruta hacia el controlador y compruebo que exista
$ruta = "controladores/{$controlador}.php";
if (!file_exists($ruta)) {
    die("Error de acceso al controlador.");
}

//Importo y instancio el controlador
require_once $ruta;
$instancia = new $controlador;  //Recuerda poner primera mayuscula

//Comprueba que el metodo exista y ejecutalo
if (method_exists($instancia, $f)) {
    $instancia->$f();
}else{
    die("Error del controlador: $controlador con metodo $f.");
}


?>


