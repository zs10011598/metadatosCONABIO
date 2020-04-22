<?php
	
	$url_api  = "https://dev-tsdx-lc7.us.webtask.io/adf6e2f2b84784b57522e3b19dfc9201/api/";
	$url_token = "https://dev-tsdx-lc7.auth0.com/oauth/token";
	$url_management = "https://dev-tsdx-lc7.auth0.com/api/v2/";

	require('php/conexion.php');

	if(isset($_POST['token'])){

		$token = $_POST['token'];
		$email = $_POST['email'];
		$name  = $_POST['nombre'];
		$lname = $_POST['apellido'];
		$pass  = $_POST['pass'];
		$puesto = $_POST['puesto'];
		$phone = $_POST['telefono'];


		$postString = "grant_type=client_credentials&client_id=Q44jiWffE6ev2QpsUicQRMqM3qZj4whx&client_secret=hchUJFZ-D1Uw19jZadGcRxW28A_-vdCcNfKc-pd9jo9xY_73UkIRVBSYa4ToQzip&audience=https://dev-tsdx-lc7.auth0.com/api/v2/";
		$ch = curl_init($url_token);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json =  json_decode($response, true);
		$token_1 = $response_json['access_token'];


		$ch = curl_init($url_management.'users');
		$headers = [
		    'content-type: application/json',
		    'authorization: Bearer '.$token_1
		];
		$data = array('email' => $email,
					  'given_name' => $name,
					  'family_name' => $lname,
					  'password' => $pass,
					  'connection' => 'Username-Password-Authentication');
		$data_json = json_encode($data);
		print_r($data_json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json = json_decode($response, true);


		$user_id = $response_json['user_id'];



		$db = conectar();
		if($db){
			$sql = 'INSERT INTO analistas("Persona", "Puesto", "Telefono", "mail", "nom_user", "password", "activo", "id_auth0") VALUES(\''.$name.' '.$lname.'\', \''.$puesto.'\', \''.$phone.'\', \''.$email.'\', \'\', \''.$pass.'\', 1, \''.$user_id.'\')';
			$result = pg_query($db, $sql); 
		}
		

		$headers = [
			'content-type: application/json',
		    'authorization: Bearer '.$token];
		$data = "[\"ff367830-e97c-49ac-8c9a-aa441a64228d\"]";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_api.'users/'.$user_id.'/roles');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		
		$response_json = json_decode($response, true);


	}



?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<div id="gated-content" style="display: none;">
		<button id="btn-login" disabled="true" onclick="login()" class="bttnIni cn" style="display: none;">Iniciar Sesi&oacute;n</button>
    	<form id="myForm" action="Menu.php" method="POST">
    		<p style="text-align: center;">
	    		<span id="ipt-user-profile"></span>
		    </p>
		    <input type="text" id="token" name="token" style="display: none;">
		    <input type="text" id="user" name="user" style="display: none;">
		    <input type="submit" name="enviar" class="bttnIni cn" value="Entrar" style="display: none;">
    	</form>
    	<button id="btn-logout" style="display: none;" onclick="logout()" class="bttnIni cn" style="display: none;">Salir</button>
	</div>

	<script src="https://cdn.auth0.com/js/auth0-spa-js/1.2/auth0-spa-js.production.js"></script>
  	<script src="public/js/app.js"></script>
	<script type="text/javascript">
		configureClient();
		login();
		updateUI();
	    document.getElementById('myForm').submit();
	</script>

</body>
</html>