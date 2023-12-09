<?php
function validarNombre($nombre) {
    // el nombre debe tener un maximo de 20 caracteres
    if (strlen($nombre) > 20) {
        return false;
    }
    return true;
}

function validarApellido($apellido) {
    // el apellido debe tener un maximo de 20 caracteres
    if (strlen($apellido) > 20) {
        return false;
    }
    return true;
}

function validarTelefono($telefono) {
    // el telefono debe tener un maximo y minimo de 9 caracteres
    if (strlen($telefono) < 9 || strlen($telefono) > 9) {
        return false;
    }
    return true;
}

function validarEmail($email) {
    // el email debe ser un email valido y tener un maximo de 30 caracteres
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 30) {
        return false;
    }
    return true;
}

function validarPassword($password) {
    // la contraseña debe tener un maximo de 255 caracteres
    if (strlen($password) > 255) {
        return false;
    }
    return true;
}

function validarDNI($dni) {
    // el DNI debe cumplir con el patron de 8 letras mayusculas seguidas de 1 numero
    $pattern = "/^[0-9]{8}[A-Z]{1}$/";
    if (!preg_match($pattern, $dni)) {
        return false;
    }
    return true;
}
?>