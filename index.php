<?php

//  CONTROLADOR FRONTAL  //

//Inicion session para toda la web
session_start();

// Compruebo los metodos de envio y en ausencia de ellos redirigio al login
// Esto se encarga de recoger cualquier informacion de los formularios 
// que hayan direccionado hasta index

$f = $_GET["funcion"]??$_POST["funcion"]??"renderLogin";         //Por defecto renderizamos el login siempre en ausencia de otra orden
$c = $_GET["controlador"]??$_POST["controlador"]??"Usuario";

//Comprobacion de sesion timeout forzando el cierre de sesion y redirigiendo al login
//El expire se guarda en la sesion con el tiempo de inicio de sesion + 5 minutos en el metodo loginUsuario del controlador Usuario
if (isset($_SESSION["expira"]) && $_SESSION["expira"] < time()) {
    //la sesion ha expirado, la destruyo y la inicio de nuevo
    session_destroy();
    session_start();
    // Redirigir al usuario a la pagina de inicio de sesión
    $f = "renderLogin";
    $c = "Usuario";
}

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


