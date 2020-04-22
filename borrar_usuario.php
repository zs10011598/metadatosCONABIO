<?php 

	require('php/conexion.php');
	$db = conectar();
	$url_api  = "https://dev-tsdx-lc7.us.webtask.io/adf6e2f2b84784b57522e3b19dfc9201/api/";
	$url_token = "https://dev-tsdx-lc7.auth0.com/oauth/token";
	$url_management = "https://dev-tsdx-lc7.auth0.com/api/v2/";
	
	 if(isset($_GET['user_id'])){

	 	$id_user = $_GET['user_id'];
	 	$sql = "UPDATE analistas SET activo = '0' WHERE id_auth0 = '".$id_user."'";
	 	$res = pg_query($db, $sql);

	 	if (!$res) { 
	 		
	 		echo '{"ok":false, "message": "ERROR: No se pudo desactivar usuario en DB"}'; 
	 	
	 	} else {

			$postString = "grant_type=client_credentials&client_id=Q44jiWffE6ev2QpsUicQRMqM3qZj4whx&client_secret=hchUJFZ-D1Uw19jZadGcRxW28A_-vdCcNfKc-pd9jo9xY_73UkIRVBSYa4ToQzip&audience=https://dev-tsdx-lc7.auth0.com/api/v2/";
			$ch = curl_init($url_token);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_json =  json_decode($response, true);
			$token_1 = $response_json['access_token'];

			if($token_1 === ''){

				echo '{"ok":false, "message": "ERROR: No se pudo obtener el token para ek API auth0"}';

			} else {

				$ch = curl_init($url_management.'users/'.$id_user);
				$headers = [
				    'content-type: application/json',
				    'authorization: Bearer '.$token_1
				];
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$response_json = json_decode($response, true);

				
				echo '{"ok":true, "message": "SUCCESS"}';
			}

	 	} 

	 } else {

	 	echo '{"ok":false, "message": "No fue provisto un ID de metadato"}'; 

	 }

?>