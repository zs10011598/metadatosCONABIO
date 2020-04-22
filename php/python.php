<?php

ini_set("display_errors", "on");
header('Content-Type: text/html; charset=utf-8'); 

$firstname = $_GET["firstname"];
$lastname = $_GET["lastname"];
$password = $_GET["password"];
echo "nombre: $firstname primer apellido: $lastname contraseña: $password";

?>
