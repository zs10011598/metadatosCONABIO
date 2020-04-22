<?php 

	require('php/conexion.php');
	$db = conectar();
	$url_api  = "http://zacatuche.conabio.gob.mx:4000/";

	 if(isset($_GET['q'])){

	 	$q = $_GET['q'];
	 	
		$ch = curl_init($url_api);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('query'=>'query{   searchTaxon(q:"'.$q.'"){    totalCount     edges{       node{         id         categoria         taxon         fuente         bibliografia     		arbolTaxonomico{           id           taxon           categoria         }       }     }   } }') );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json =  json_decode($response, true);

		echo $response;

	 } else {

	 	echo '{"ok":false, "message": "No fue provisto un ID de metadato"}'; 

	 }

?>
