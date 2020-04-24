<?php


	$q = $_GET['q'];
	$endpoint = "http://zacatuche.conabio.gob.mx:4000/";
	$qry = '{"query": "query {searchTaxon(q:\"'.$q.'\") { totalCount edges{ node{ id taxon categoria arbolTaxonomico{ id taxon categoria } } } } }" }';

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $qry);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	var_dump($result);

	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	
	echo $result;

?>