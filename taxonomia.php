<?php 

	require('php/conexion.php');
	$db = conectar();
	$endpoint = "http://zacatuche.conabio.gob.mx:4000/";

	 if(isset($_GET['q'])){

	 	$q = $_GET['q'];
		$qry = '{"query": "query {searchTaxon(q:\"'.$q.'\") { totalCount edges{ node{ id taxon categoria nombreAutoridad bibliografia nombresComunes { nombreComun } arbolTaxonomico{ id taxon categoria } } } } }" }';

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		//var_dump($result);
		echo $result;

	 } else {

	 	echo '{"ok":false, "message": "No fue provisto un ID de metadato"}'; 

	 }

?>
