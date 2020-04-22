<?php  
require_once("java/Java.inc"); 
ini_set("display_errors", "on");

function conectar()
{
	$db = pg_pconnect("host=localhost port=5432 dbname=metadatos user=postgres password=sig123456") or die( "Error al conectar a Metadatos: ".pg_last_error() );
   	if (!$db) { exit('Error en la conexión a la base de datos METADATOS'); } 
   	else{ return $db;} 
}

function catalogos()
{
	$cat = pg_pconnect("host=localhost port=5432 dbname=catalogos user=postgres password=sig123456") or die( "Error al conectar a Catalogos: ".pg_last_error() );
   	if (!$cat) { exit('Error en la conexión a la base de datos CATALOGOS'); } 
   	else{ return $cat;} 
}
?>