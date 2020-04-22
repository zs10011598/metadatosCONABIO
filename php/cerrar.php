<?php 

	require('conexion.php');
	$db = conectar();

	 if(isset($_GET['metadato'])){

	 	$id_metadato = $_GET['metadato'];

	 	$sql = "UPDATE coberturas SET abierto = false WHERE record_id = ".$id_metadato;

	 	$res = pg_query($db, $sql);

	 	if (!$res) { 
	 		
	 		echo '{"ok":false, "message": "ERROR"}'; 
	 	
	 	} else { 

	 		echo '{"ok":true, "message": "SUCCESS"}';

	 	} 

	 } else {

	 	echo '{"ok":false, "message": "No fue provisto un ID de metadato"}'; 

	 }

?>