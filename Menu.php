<?php 
session_start();

$url_api  = "https://dev-tsdx-lc7.us.webtask.io/adf6e2f2b84784b57522e3b19dfc9201/api/";
$url_token = "https://dev-tsdx-lc7.auth0.com/oauth/token";
$token = '';
//$_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid'])
//print_r($_POST['token']);
//print_r($_POST);

if ( ! ($_POST['token'] != '' && isset($_POST['user'])) )
{

	header('Location: index.php');
}
else
{
	ini_set("display_errors", "on");
	header('Content-Type: text/html; charset=utf-8'); 
	require('php/conexion.php');
	require('php/genera.php');
	
	$db = conectar();
	if ($db)
	{
		//$sql = 'SELECT * FROM analistas where "idAnalista"='.$_SESSION['uid'].';';
		//$result = pg_query($db, $sql); 
		//if (!$result) { exit("Error en la consulta"); } 
		
		/*if( $fila = pg_fetch_array($result) )
		$cv_principal = $fila['idAnalista']; 	
		$nombreUsuario = $fila['Persona'];*/


		$user = json_decode($_POST['user']);
		$cv_principal = $user->sub; 
		$nombreUsuario = $user->nickname;
		echo  "<input type=\"hidden\" name=\"cv_principal\"  id=\"cv_principal\" value= \"$cv_principal\"/>";	
		echo  "<input type=\"hidden\" name=\"id_metadato\"   id=\"id_metadato\" value= \"0\" />";	
 
		$postString = "grant_type=client_credentials&client_id=f9ZYgYdWITNKOn0KZC5GnN0pNvJVtcI8&client_secret=WQS7wUtb5SD5_o1fkEvSn_IWpW0AgBYxgA-W11CX35ZOsRSZNk4EizwJYj96cj1f&audience=urn:auth0-authz-api";

		$ch = curl_init($url_token);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json =  json_decode($response, true);
		
		$token = $response_json['access_token'];


		$ch = curl_init($url_api.'users/'.$cv_principal.'/roles');
		$headers = [
		    'content-type: application/json',
		    'authorization: Bearer '.$token
		];
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json =  json_decode($response, true);
		$roles = $response_json;

		$ch = curl_init($url_api.'roles');
		$headers = [
		    'content-type: application/json',
		    'authorization: Bearer '.$token
		];
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json = json_decode($response, true);
		
		$all_roles = $response_json['roles'];

		//print_r($all_roles);

		$permissions = array();
		foreach ($all_roles as $role) {
			
			foreach ($roles as $orole) {
				
				if($orole['_id'] == $role['_id']){

					$permissions = array_merge($permissions, $role['permissions']);

				}

			}

		}

		//print_r($permissions);

		$ch = curl_init($url_api.'users');
		$headers = [
		    'content-type: application/json',
		    'authorization: Bearer '.$token
		];
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_json =  json_decode($response, true);
		$users = $response_json['users'];

	}


	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html >

  <head>
    <title>Menu</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="css/datepicker.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="CSS/style.css" media="all" />>-->
   
    <link rel="stylesheet" href="css/jquery.ui.core.css">
    <link rel="stylesheet" href="css/jquery.ui.dialog.css">
    <link rel="stylesheet" href="css/jquery.ui.button.css">
    
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    
	<script src="jquery/jquery-1.7.1.min.js"></script>
    <script src="jquery/jquery-ui-1.10.4.custom.js"></script>
    <script src="jquery/jquery.ui.datepicker-es.js"></script>
    <script src="jquery/jquery.easyui.min.js"></script>
    <script src="jquery/ui/jquery.ui.core.js"></script>
	<script src="jquery/ui/jquery.ui.widget.js"></script>
	<script src="jquery/ui/jquery.ui.position.js"></script>
	<script src="jquery/ui/jquery.ui.button.js"></script>
	<script src="jquery/ui/jquery.ui.dialog.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
	<!--<script src="js/javascript.js"></script>
    <script src="Javascript/jquery.ui.datepicker-es.js"></script>
    <script src="Javascript/jquery-ui-1.10.4.custom.js"></script>
    
	<script src="ajax.js"></script>
  	<script src="Javascript/scriptMetadatos.js"></script>-->

<style>
html, body, div, h1, h2, h3,p, a, em, font, img,ol, ul, li, form, label, table {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-weight: inherit;
  font-style: inherit;
  font-size: 100%;
  font-family: inherit;
 }
 
h1 {
  margin-bottom:15px;
  color:#ffffff;
  border:outset;
  background:#8A0808;
  cursor:pointer;
  font-weight:bold;
  font-size:1.1em;
  margin-top:15px;
  text-align:center;
  }
  
h3{
  margin-bottom:15px;
  font-size:1em;
  font-weight:bold;
  margin-top:10px;
  margin-left:10px;
  color: #8A0808;
  font-family: Verdana, Geneva, sans-serif;
 }

/*Estilo de divs*/
#hd  {
  width:100%;
  height:100px;
  background-image:url(img/fondo3.png);
 }

#nu{
  position:relative;
  top:3px;
  width:280px;
	
 }

#lf{
  position:relative;
  top:30px;
  width:280px;
 }

#lf2{
  margin-right:15px;
  width:280px;
 }
 
#autores{
  float:right;
  margin-right:50px;
  margin-left:920px;
  margin-top:80px;
  position:absolute;
  font-weight:bold;
  text-align:center;
 }
 
#rg {
  position:absolute;
  left:300px;
  top:130px;
  width:70%;
 }

#rg > div{
  display:none;
  z-index:1;
  border: 1px solid #393939;
 }
 
#div1 , #div2 , #div3, #div4, #div5, #div6, #div7, #div8, #div9{
  position:absolute;
 }
 
/*Estilo de clases*/
.txtN1 {
  font-family: Verdana, Geneva, sans-serif;
  font-size: 1.1em;
  color: #333;
  text-align:center;
  font-weight:bold;
 }
 
.txtN2 {
  font-family: Verdana, Geneva, sans-serif;
  font-size: 0.9em;
  color: #333;
  text-align:center;
  font-weight:bold;
 }

.error {
	width:120px;
	margin: 3px 0 2px 105px;
	background: rgba(221, 75, 57, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background: rgba(221, 75, 57, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background-size: 20px;
	border-radius: 5px;
	padding: 8px 25px;
	font-size: 14px;
	color: #eee;
	text-align: center;
 }
 
.guardar {
	width:140px;
	margin: 3px 0 2px 105px;
	background: rgba(175, 255, 54, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background: rgba(175, 255, 54, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background-size: 20px;
	border-radius: 5px;
	padding: 8px 25px;
	font-size: 14px;
	color: #0000;
	text-align: center;
 }
 
#validaError {
  position:absolute;
  margin-left:655px;
  top:100px;
  width:250px;
  height:10px;
  border-style: solid;
  border-width: 1px;
   
 }
 
.clsAnchoTotal{
width: 98%;
 }

.clsAgregarFila{
	position:relative;
	right:22px;
 }
 
 /*Estilo de input, button select,  texarea,  , p*/
input[type="text"]{
border: 1px solid #393939;
border-radius: 5px 5px 5px 5px;
padding: 5px;
display: inline-block;
 }

input[type="button"]{
display:block;
cursor: pointer;
font-family: inherit;
font-size: 100%;
margin-bottom:15px;
margin-left:40px;
width:180px;
height:25px;
border-radius: 5px 5px 5px 5px;
 }

textarea {
	vertical-align:text-top;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width: 97%;
	height: 60px;
	-webkit-resize: none;
	-moz-resize: none;
	resize: none;
	overflow: auto;
	border: 1px solid #393939;
	font-weight:100;
 }
 
select{
border: 1px solid #393939;
width:185px;
height:25px;
border-radius: 5px 5px 5px 5px ;
 }
 
/*Estilo de botones de guardar*/
#form1 , #form2, #form3, #form4, #form5, #form6, #form7, #form8 , #form9{
  margin-left:940px;
  position:absolute;
  z-index:2;
  cursor: pointer; 
  } 
 
/*Estilo de los tamaños de clases los input's */

.text{
width:97%;
height:25px;
  }
 
.fecha , #c_version, #c_edition, #c_escala, #c_clave, #c_avance, #c_mantenimiento , #c_tamano{
	width:86%;
	height:25px; 
 }
 
/*Estilo de los tamaños de id's los input's */
#seleccion{
  width:280px;
  height:25px;
  border-radius: 5px 5px 5px 5px;
 }
 

input, textarea, select, button {
  font-size: 100%;
  font-family: inherit;
 }

.extenso {
width: 99%;
 } 


/*Estilo Arbol*/

.tree {
  margin: 0;
  padding: 0;
  list-style-type: none;
}
.tree li {
  white-space: nowrap;
}
.tree li ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
.tree-node {
  height: 18px;
  white-space: nowrap;
  cursor: pointer;
}
.tree-hit {
  cursor: pointer;
}
.tree-expanded,
.tree-collapsed,
.tree-folder,
.tree-file,
.tree-checkbox,
.tree-indent {
  display: inline-block;
  width: 16px;
  height: 18px;
  vertical-align: top;
  overflow: hidden;
}
.tree-expanded {
  background: url('images/tree_icons.png') no-repeat -18px 0px;
}
.tree-expanded-hover {
  background: url('images/tree_icons.png') no-repeat -50px 0px;
}
.tree-collapsed {
  background: url('images/tree_icons.png') no-repeat 0px 0px;
}
.tree-collapsed-hover {
  background: url('img/tree_icons.png') no-repeat -32px 0px;
}
.tree-lines .tree-expanded,
.tree-lines .tree-root-first .tree-expanded {
  background: url('img/tree_icons.png') no-repeat -144px 0;
}
.tree-lines .tree-collapsed,
.tree-lines .tree-root-first .tree-collapsed {
  background: url('img/tree_icons.png') no-repeat -128px 0;
}
.tree-lines .tree-node-last .tree-expanded,
.tree-lines .tree-root-one .tree-expanded {
  background: url('img/tree_icons.png') no-repeat -80px 0;
}
.tree-lines .tree-node-last .tree-collapsed,
.tree-lines .tree-root-one .tree-collapsed {
  background: url('img/tree_icons.png') no-repeat -64px 0;
}
.tree-line {
  background: url('img/tree_icons.png') no-repeat -176px 0;
}
.tree-join {
  background: url('img/tree_icons.png') no-repeat -192px 0;
}
.tree-joinbottom {
  background: url('img/tree_icons.png') no-repeat -160px 0;
}
.tree-folder {
  background: url('img/tree_icons.png') no-repeat -208px 0;
}
.tree-folder-open {
  background: url('img/tree_icons.png') no-repeat -224px 0;
}
.tree-file {
  background: url('img/tree_icons.png') no-repeat -240px 0;
}
.tree-loading {
  background: url('img/loading.gif') no-repeat center center;
}
.tree-checkbox0 {
  background: url('img/tree_icons.png') no-repeat -208px -18px;
}
.tree-checkbox1 {
  background: url('img/tree_icons.png') no-repeat -224px -18px;
}
.tree-checkbox2 {
  background: url('img/tree_icons.png') no-repeat -240px -18px;
}
.tree-title {
  font-size: 12px;
  display: inline-block;
  text-decoration: none;
  vertical-align: top;
  white-space: nowrap;
  padding: 0 2px;
  height: 18px;
  line-height: 18px;
}
.tree-node-proxy {
  font-size: 12px;
  line-height: 20px;
  padding: 0 2px 0 20px;
  border-width: 1px;
  border-style: solid;
  z-index: 9900000;
}
.tree-dnd-icon {
  display: inline-block;
  position: absolute;
  width: 16px;
  height: 18px;
  left: 2px;
  top: 50%;
  margin-top: -9px;
}
.tree-dnd-yes {
  background: url('img/tree_icons.png') no-repeat -256px 0;
}
.tree-dnd-no {
  background: url('img/tree_icons.png') no-repeat -256px -18px;
}
.tree-node-top {
  border-top: 1px dotted red;
}
.tree-node-bottom {
  border-bottom: 1px dotted red;
}
.tree-node-append .tree-tiLynxtle {
  border: 1px dotted red;
}
.tree-editor {
  border: 1px solid #ccc;
  font-size: 12px;
  height: 14px !important;
  height: 18px;
  line-height: 14px;
  padding: 1px 2px;
  width: 80px;
  position: absolute;
  top: 0;
}
.tree-node-proxy {
  background-color: #ffffff;
  color: #000000;
  border-color: #95B8E7;
}
.tree-node-hover {
  background: #eaf2ff;
  color: #000000;
}
.tree-node-selected {
  background: #FBEC88;
  color: #000000;
} 

#name { margin-bottom:12px; width:95%; padding: .4em; }
#nameDuplica { margin-bottom:12px; width:95%; padding: .4em; }
.ui-dialog .ui-state-error { padding: .3em; }
.validateTips , .validateDelete , .validateNuevo{ 
	border: 1px solid transparent; 
	padding: 0.3em;
	margin-left:150px; 
	} 
.validateDelete{
	margin-left:100px;
	text-align:center;
}

.validateNuevo {
margin-left:10px;
	text-align:center;
}

 #name{
margin-left:15px;
	text-align:center;
}
.alert{ 
 position:absolute;
 width:60px;
 height:60px;
 background: #ECEADF;
 left:40px;


}
 
</style>
<script type="text/javascript">
$(document).ready(function(){

	
	$('#reabrir-metadato').click(function(){

		$( "#reabrir-modal-container" ).dialog
		({
			autoOpen: false,
			height: 250,
			width: 450,
			modal: true,
			buttons: 
			{
				"Reabrir metadato": function() 
				{

					//console.log($("#reabrir-metadato-form").html());
					
					$.ajax({
						url: 'php/reabrir.php?metadato=' + $('#reabrir-metadato-hidden').val(), 
						type: 'GET', 
						dataType : "json",
						contentType: 'application/json',
						}).done(function(result) {
							
							console.log(result);
							if(result['ok']){
								console.log('Metadato reabierto!!!');	
								open_inputs_metadato();
							} else {
								console.log(result['message']);
							}
							
						}); 
					
					$( this ).dialog( "close" );
				
				},
				
				"Cancelar": function() {	$( this ).dialog( "close" );	}
			},
			
			close: function() {		allFields.val( "" ).removeClass( "ui-state-error" );	}
		});
			
		$( "#reabrir-modal-container" ).dialog( "open" );
	});

	$('#cerrar-metadato').click(function(){

		$( "#cerrar-modal-container" ).dialog
		({
			autoOpen: false,
			height: 250,
			width: 450,
			modal: true,
			buttons: 
			{
				"Cerrar metadato": function() 
				{

					//console.log($("#reabrir-metadato-form").html());
					
					$.ajax({
						url: 'php/cerrar.php?metadato=' + $('#cerrar-metadato-hidden').val(), 
						type: 'GET', 
						dataType : "json",
						contentType: 'application/json',
						}).done(function(result) {
							
							console.log(result);
							if(result['ok']){
								console.log('Metadato cerrado!!!');	
								close_inputs_metadato();
							} else {
								console.log(result['message']);
							}
							
						}); 
					
					$( this ).dialog( "close" );
				
				},
				
				"Cancelar": function() {	$( this ).dialog( "close" );	}
			},
			
			close: function() {		allFields.val( "" ).removeClass( "ui-state-error" );	}
		});
			
		$( "#cerrar-modal-container" ).dialog( "open" );
	});


	$('#borrar-usuario-select').on('input', function(){

		console.log($(this).val());

		if($(this).val() !== '' ) {

			$('#borrar-usuario-button').prop('disabled', false);
			
		} else {
			
			$('#borrar-usuario-button').prop('disabled', true);	

		}

		$('#borrar-usuario-hidden').val($(this).val());

	});

	$('#buscar-taxonomia').click(function(){

		if($('#palabra-clave').val().length <= 3){

			alert('Ingrese mas letras');

		} else {

			$.ajax({
			url: 'taxonomia.php?q=' + $('#palabra-clave').val(), 
			type: 'GET', 
			dataType : "json",
			contentType: 'application/json',
			}).done(function(result) {
				
				$('#toxonomy-results tbody').empty();

				var total_results = result['data']['searchTaxon']['totalCount'];

				if(total_results == 0){

					alert('No se encontraron resultados');

				} else {

					var results = result['data']['searchTaxon']['edges'];
					for (i=0;i<total_results;i++){

						if(results[i] != null){

							if(results[i]['node'] !== null && results[i]['node']['categoria'] == 'especie'){
								console.log(results[i]);

								var taxo = '<ul>';
								var biblio = '<ul>'
								var reino = '';
								var phylum = '';
								var clase = '';
								var orden = '';
								var familia = '';
								var genero = '';

								results[i]['node']['arbolTaxonomico'].forEach(tax => {

									if(tax['categoria'] == 'Reino'){
										reino += '<li>' + 
													'<b>Reino:</b> ' + tax['taxon'] +
												'</li>';
									}

									if(tax['categoria'] == 'phylum'){
										phylum += '<li>' + 
													'<b>Phylum:</b> ' + tax['taxon'] +
												'</li>'
									}

									if(tax['categoria'] == 'clase'){
										clase += '<li>' + 
													'<b>Clase:</b> ' + tax['taxon'] +
												'</li>'
									}

									if(tax['categoria'] == 'orden'){
										orden += '<li>' + 
													'<b>Orden:</b> ' + tax['taxon'] +
												'</li>'
									}

									if(tax['categoria'] == 'familia'){
										familia += '<li>' + 
													'<b>Familia:</b> ' + tax['taxon'] +
												'</li>'
									}

									if(tax['categoria'] == "género"){
										genero += '<li>' + 
													'<b>Género:</b> ' + tax['taxon'] +
												'</li>'
									}

								});

								taxo += reino + phylum + clase + orden + familia + genero;
								taxo += '<li>' + 
											'<b>Especie:</b> ' + results[i]['node']['taxon'] +
										'</li>';

								results[i]['node']['bibliografia'].forEach(bib => {

									biblio += '<li>' +
													bib +
											 '</li>';

								});

								taxo += '<ul>';
								biblio += '</ul>';

								$('#toxonomy-results tbody').append(
									'<tr>' +
										'<td id="taxo-' + i + '">' +
											taxo + 
										'</td>' +
										'<td id="biblio-' + i + '">' +
											biblio +
										'</td>' +
										'<td>' +
											'<button type="button" class="add-tax" id="tax-' + i + 
											'">Agregar</button>' +
										'</td>' +
									'</tr>'
								);

							}

						}
					}


				}

				
			}); 

		}

	});


	$('#borrar-usuario-button').click(function(){

		$( "#borrar-usuario-modal-container" ).dialog
		({
			autoOpen: false,
			height: 250,
			width: 450,
			modal: true,
			buttons: 
			{
				"Borrar usuario": function() 
				{

					$.ajax({
						url: 'borrar_usuario.php?user_id=' + $('#borrar-usuario-hidden').val(), 
						type: 'GET', 
						dataType : "json",
						contentType: 'application/json',
						}).done(function(result) {
							
							console.log(result);
							if(result['ok']){
								console.log('Usuario borrado!!!');

								$('#borrar-usuario-select').empty();
								$('#borrar-usuario-select').append('<option value="">Seleccione un usuario</option>');

								<?php

									$ch = curl_init($url_api.'users');
									$headers = [
									    'content-type: application/json',
									    'authorization: Bearer '.$token
									];
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									$response = curl_exec($ch);
									curl_close($ch);
									$response_json =  json_decode($response, true);
									$users = $response_json['users'];

					     			foreach ($users as $u) {
					     				echo '$("#borrar-usuario-select").append(\'<option value="'.$u['user_id'].'">'.$u['name'].'</option>\');';
					     			}

					     		?>

							} else {
								console.log(result['message']);
							}
							
						}); 
					
					$( this ).dialog( "close" );
				
				},
				
				"Cancelar": function() {	$( this ).dialog( "close" );	}
			},
			
			close: function() {		allFields.val( "" ).removeClass( "ui-state-error" );	}
		});
			
		$( "#borrar-usuario-modal-container" ).dialog( "open" );
	});



	$( ".error" ).hide();
	$( ".guardar" ).hide();
	$( "#otro" ).hide();
	
	$('#descarga').click(function(){
		window.location = "ficheros/metadatos.sfx.exe";
	});

	var clave = $("input[name = cv_principal]").val();
	var idMetadato	= $("input[name = id_metadato]").val();
	
	<?php
		if( isset( $_POST['id_metadato'] ) && $_POST['id_metadato'] != '' && isset( $_POST['cv_principal'] ) && $_POST['cv_principal'] != '' ){
			$cv_principal = $_POST['cv_principal'] ;
			$id_metadato = $_POST['id_metadato'] ;
			$displayBlock = $_POST["displayBlock"];
			
			echo "displayBlock($displayBlock);";
			echo "metadato($id_metadato, $cv_principal);";
			echo "verTablas($id_metadato, $cv_principal);";
			echo "generaList($cv_principal, $id_metadato);";
			echo "generaLoc($cv_principal, $id_metadato);";
			echo "generaMunis($cv_principal, $id_metadato);";
			echo "generaEdos( $id_metadato);";
			echo "generaTablas($cv_principal, 'datosOrigin' , $id_metadato);";
			echo "generaTablas($cv_principal, 'datosTaxon' , $id_metadato);";
			echo "generaTablas($cv_principal, 'formAtributos' , $id_metadato);";
			echo "generaArbol($cv_principal, 'divClasificacion' , $id_metadato);";
			echo "guardarAlert( 'El Metadato a sido actualizado.');";
			
		}
		if( isset( $_POST['msgs_actualiza'] ) && $_POST['msgs_actualiza'] != '' && isset( $_POST['cv_principal'] ) && $_POST['cv_principal'] != ''){
			echo "generaList($cv_principal, 0);";
			echo "guardarAlert( 'El Metadato a sido eliminado.');";
		}
	?>
	generaList(clave , 0);
	generaEdos(0);

	
	$(".fecha").datepicker ({
		changeYear: true,
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat: 'dd/mm/yy',
	});	
	
	$("#seleccion").change(function () {
    	$("#seleccion option:selected").each(function () {
			var cv_principal=$(this).val();
			if(cv_principal != "")	{	
				$('#id_metadato').val(cv_principal);
				metadato(cv_principal , clave);	
				verTablas(cv_principal , clave);
				generaTablas(clave , "datosOrigin" ,cv_principal );
				generaTablas (clave ,"datosTaxon", cv_principal);
				generaTablas (clave ,"formAtributos", cv_principal);
				generaArbol (clave ,"divClasificacion", cv_principal);
				
				$('option', '#estado').remove();
				$('option', '#municipio').remove();
				$('option', '#localidad').remove();
				$('#estado').append('<option value="0">Seleccione un estado</option>');
				$('#municipio').append('<option value="0">Seleccione un municipio</option>');
				$('#localidad').append('<option value="0">Seleccione una localidad</option>');
				
				generaLoc(0 , cv_principal)
				generaMunis(0 , cv_principal)
				generaEdos(cv_principal);
				
				
			}
			else { $(location).attr('href','Menu.php');}
		});
	});
	
	$("#estado").change(function () {
    	$("#estado option:selected").each(function () {
			id_edo=$(this).val();
			if(id_edo != 0)	{	
				generaMunis(id_edo , 0 );
				if(id_edo == '33'){$("#otro").show(); }	
				else{$("#otro").hide(); }	
				$('#c_pubplace').val('');
			}
			else {	
				$('option', '#municipio').remove();
				$('option', '#localidad').remove();
				$('#municipio').append('<option value="0">Seleccione un municipio</option>');
				$('#localidad').append('<option value="0">Seleccione una localidad</option>');
			}
		});
	});
	
	$("#municipio").change(function () {
    	$("#municipio option:selected").each(function () {
			id_mun =$(this).val();
			if(id_mun != 0)	{	generaLoc(id_mun , 0);	}
			else {
				$('option', '#localidad').remove();
				$('#localidad').append('<option value="0">Seleccione una localidad</option>');
			}
			
		});
	});
	
	$("#selectVector").change(function () {
    	$("#selectVector option:selected").each(function () {
			elegido=$(this).val();
			if(elegido!=0)	
			{	vectores(elegido);	}
			else
			{	limpiarGeoinfo();	}
		});
	});
	
	$("#selectTif").change(function () {
    	$("#selectTif option:selected").each(function () {
			elegido=$(this).val();
			if(elegido != 0)	
			{	archivosTif(elegido);}
			else
			{	limpiarGeoinfo();	}
		});
	});
	
	$("#form1").click(function () {
		guarda = validaform1();
		if (typeof(guarda) == 'undefined'){	
			$( "#datos" ).submit();
		}
	});
	
	$("#form2").click(function () {
		guarda2 = validaform2();
		if (typeof(guarda2) == 'undefined'){	
			$( "#restricciones" ).submit();
		}
	});
	
	$("#form3").click(function () {
		guarda3 = validaform3();
		if (typeof(guarda3) == 'undefined'){	
			$( "#palabrasClave" ).submit();
		}
	});
	
	$("#form4").click(function () {
		$( "#ambiente" ).submit();
	});
	
	$("#form5").click(function () {
		guarda5 = validaform5();
		if (typeof(guarda5) == 'undefined'){	
			$( "#calidad" ).submit();
		}
	});
	
	$("#form6").click(function () {
		$( "#taxon" ).submit();
	});
	
	var fileExtension = "";
	$(':file').change(function(){
		var file = $("#userfile")[0].files[0];
		var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var fileType = file.type;
		if(fileExtension == "txt")
		{
			var formData = new FormData($(".formulario")[0]);
			var message = "";
			
			$.ajax({
				url: 'php/subir.php', 
				type: 'POST', 
				data: formData,
				dataType : "json",
				cache: false,
				contentType: false,
				processData: false,
				}).done(function(result) {
					
					$('option', '#selectVector').remove();
					$('option', '#selectTif').remove();
					
					$('#selectVector').append('<option value="0">Seleccione un archivo</option>');
					$('#selectTif').append('<option value="0">Seleccione un archivo</option>');
				
					$.each(result.vector.linea, function( i, obj ) {
						$("#selectVector").append('<option value='+ obj+'>'+ result.vector.name [i]+'</option>');
					
					});
					
					$.each(result.tif.linea, function( i, obj ) {
						$("#selectTif").append('<option value='+ obj+'>'+ result.tif.name [i]+'</option>');
						
					});
				}); 
				
		}
		
		
	});
	
	$("#form7").click(function () {
		guarda7 = validaform7();
		if (typeof(guarda7) == 'undefined'){	
			$( "#espacial" ).submit();
		}
	});
	
	$("#form8").click(function () {
		guarda8 = validaform8();
		if (typeof(guarda8) == 'undefined'){	
			$( "#atributos" ).submit();
		}
	});
	
	$("#form9").click(function () {
			$( "#clasificacion" ).submit();
	});
	
	$("#nuevo").click(function () {
	
		var name = $( "#name" ),
			allFields = $( [] ).add( name ),
			tips = $( ".validateNuevo" );
			
		
		function updateTips( t ) 
		{
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
				setTimeout(function() {		tips.removeClass( "ui-state-highlight", 5000 );	}, 5000 );
		}

		function checkLength( o, n, min) 
		{
			if ( o.val() == "" ) 
			{
				o.addClass( "ui-state-error" );
				updateTips( "Inserte el nombre del metadato" );
				return false;
			}
			
			else if ( o.val().length <= min ) 
			{
				o.addClass( "ui-state-error" );
				updateTips( "El nombre del metadato es muy corto" );
				return false;
			} 
			 
			else 
			{
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) 
		{
			if ( !( regexp.test( o.val() ) ) ) 
			{
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} 
			else 
			{
				return true;
			}
		}

	
		$( "#dialog_nuevo" ).dialog
		({
			autoOpen: false,
			height: 250,
			width: 450,
			modal: true,
			buttons: 
			{
				"Crear metadato": function() 
				{
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( name, "username", 5);
					bValid = bValid && checkRegexp( name, /^[a-z] || [A-Z] ([0-9a-z_]) +$/i, "El nombre no debe contener carácteres ." );
					
					if ( bValid ) 	{ 	$( this ).dialog( "close" ); }
				},
				
				"Cancelar": function() {	$( this ).dialog( "close" );	}
			},
			
			close: function() {		allFields.val( "" ).removeClass( "ui-state-error" );	}
		});
		
		$( "#dialog_nuevo" ).dialog( "open" );
	});
	
	$("#borrarMetadato").click(function () {
		borrarM =validaMetadato();
		if (typeof(borrarM) == 'undefined')
		{
			$( "#eliminar_metadato" ).dialog
			({
				autoOpen: false,
				resizable: false,
				height: 200,
				width: 450,
				modal: true,
				buttons: 
				{
					"Continuar": function() 
					{
						$( "#formDeleteM" ).submit();
					},
					
					"Cancelar": function() {	$( this ).dialog( "close" );	}
				},
			});	
			$( "#eliminar_metadato" ).dialog( "open" );
		}
	});
	
	$( "#cerrarSesion" ).click(function() {	
		$( "#cerrar_sesion" ).dialog
		({
			autoOpen: false,
			resizable: false,
			height: 200,
			width: 450,
			modal: true,
			buttons: 
			{
				"Continuar": function() 
				{
					$( "#formTerminoSesion" ).submit(); 
				},
				
				"Cancelar": function() {	$( this ).dialog( "close" );	}
			},
		});
		$( "#cerrar_sesion" ).dialog( "open" );	
	});
	
});	


function validaform1(){
	c_nombre 		= $("input[name = c_nombre]");
	c_cobertura 	= $('input[name = c_cobertura]');
	c_fecha_inicial = $('input[name = c_fecha_inicial]');
	c_fecha 		= $('input[name = c_fecha]');
	x_origin		= $('input[name ^= x_origin]');
	c_publish 		= $('input[name = c_publish]');
	c_publish_siglas= $('input[name = c_publish_siglas]');
	estado			= $('select[name = estado]');
	estadoOtro		= $('select[name = estado]').val();
	municipio 		= $('select[name = municipio]');
	c_pubplace 		= $('input[name = c_pubplace]');
	c_pubdate 		= $('input[name = c_pubdate]');
	c_escala 		= $('input[name = c_escala]');
	c_issue 		= $('textarea[name = c_issue]');
	c_resumen		= $('textarea[name = c_resumen]');
	//c_abstract		= $('textarea[name = c_abstract]');
	c_objetivo 		= $('textarea[name = c_objetivo]');
	c_tiempo 		= $('input[name = c_tiempo]');
	c_tiempo2 		= $('input[name = c_tiempo2]');
	l_liga_www		= $('input[name ^= l_liga_www]');

		
	var validaDatos = [	[c_nombre 			, " Ingrese el título del mapa"			],
						[c_cobertura		, " Ingrese el nombre del archivo"		] ,
						[c_fecha_inicial	, " Seleccione una fecha"		] ,
						[c_fecha			, " Seleccione una fecha"] ,
						[x_origin			, " Ingrese mínimo un autor"] ,
						[c_publish			, " Ingrese el nombre de la institución"] ,
						[c_publish_siglas	, " Ingrese las siglas de la institución"] ,
						[estado				, " Seleccione un estado"] ,
						//[municipio			, " Seleccione un municipio"] ,
						//[c_pubplace			, " Ingrese el lugar de publicación"] ,
						[c_pubdate			, " Seleccione una fecha"] ,
						[c_escala			, " Ingrese la escala de mapa"] ,
						[c_issue			, " Ingrese la descripción del mapa"] ,
						[c_resumen			, " Ingrese la resumen del mapa"] ,
						//[c_abstract			, " Ingrese el abstract del mapa"] ,
						[c_objetivo			, " Ingrese los objetivos principales"] ,
						[c_tiempo			, " Seleccione una fecha"] ,
						[c_tiempo2			, " Seleccione una fecha"] ,
						[l_liga_www			, " Ingrese mínimo una liga"]
					  ];
	

							
	for (i = 0 ; i <= validaDatos.length ; i++){
		if (typeof(validaDatos[i]) !== 'undefined'){			
			if (validaDatos[i][0].val() == '' || validaDatos[i][0].val() == 0) {
					createAlert (validaDatos[i][0] , validaDatos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaDatos[i][0]);	}
		}
	}
	
		if (estadoOtro != '33')
	{
		if (municipio.val() == 0) 
			{
				createAlert (municipio ," Seleccione un municipio");
				return false;	
			}
			else	
			{
				removeAlert (municipio);
			}
	}
	else
	{
		if (c_pubplace.val() == '') 
		{
				createAlert (c_pubplace ," Ingrese el lugar de publicación");
				return false;	
		}
		else	
		{
			removeAlert (c_pubplace);
		}
	}
 }
 
function validaform2(){
	c_acceso = $("input[name = c_acceso]");
	c_uso 		= $('input[name = c_uso]');

		
	validaDatos = [	[c_acceso 	, " Ingrese la restricción de acceso"	],
					[c_uso		, " Ingrese la restricción de uso"		]
				  ];
	
							
	for (i = 0 ; i <= validaDatos.length ; i++){
		if (typeof(validaDatos[i]) !== 'undefined'){			
			if (validaDatos[i][0].val() == '' || validaDatos[i][0].val() == 0) {
					createAlert (validaDatos[i][0] , validaDatos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaDatos[i][0]);	}
		}
	}
	
 }
 
function validaform3(){

	m_Palabra_Clave		= $('input[name ^= m_Palabra_Clave]');
	s_Sitios_Clave		= $('input[name ^= s_Sitios_Clave]');

		
	var validaDatos = [	[m_Palabra_Clave 	, " Ingrese mínimo una palabra clave"		],
						[s_Sitios_Clave		, " Ingrese mínimo un sitio clave"		] ];
						
	for (i = 0 ; i <= validaDatos.length ; i++){
		if (typeof(validaDatos[i]) !== 'undefined'){			
			if (validaDatos[i][0].val() == '' || validaDatos[i][0].val() == 0) {
					createAlert (validaDatos[i][0] , validaDatos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaDatos[i][0]);	}
		}
	}
 }
 
function validaform5(){

	c_metodologia 			= $('input[name = c_metodologia]');
	c_descrip_metodologia 	= $('textarea[name = c_descrip_metodologia]');
	c_descrip_proceso		= $('textarea[name = c_descrip_proceso]');
	d_nombre 				= $('input[name ^= d_nombre]');
	d_publish				= $('input[name ^= d_publish]');
	d_siglas 				= $('input[name ^= d_siglas]');
	d_pubplace				= $('input[name ^= d_pubplace]');
	d_edition				= $('input[name ^= d_edition]');
	d_escala 				= $('input[name ^= d_escala]');
	d_pubdate 				= $('input[name ^= d_pubdate]');
	d_formato 				= $('input[name ^= d_formato]');
	d_geoform				= $('input[name ^= d_geoform]');
	h_origin 				= $('input[name ^= h_origin]');

		
	var validaDatos = [	[c_metodologia 			, " Ingrese la metodología utilizada"		],
						[c_descrip_metodologia	, " Ingrese la descripción la metodología"	],
						[c_descrip_proceso		, " Ingrese la descripción del proceso"		],
						[d_nombre				, " Ingrese el título del dato"				],
						[d_publish				, " Ingrese la intitución responsable"		],
						[d_siglas				, " Ingrese las siglas de la institución"	],
						[d_pubplace				, " Ingrese el lugar de publicación"		],
						[d_edition				, " Ingrese la versión"						],
						[d_escala				, " Ingrese la escala"						],
						[d_pubdate				, " Ingrese la fecha"						],
						[d_formato				, " Ingrese el formato utilizado"			],
						[d_geoform				, " Ingrese dato geoespacial"				],
						[h_origin				, " Ingrese mínimo un autor"						]
					];
						
	for (i = 0 ; i <= validaDatos.length ; i++){
		if (typeof(validaDatos[i]) !== 'undefined'){			
			if (validaDatos[i][0].val() == '' || validaDatos[i][0].val() == 0) {
					createAlert (validaDatos[i][0] , validaDatos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaDatos[i][0]);	}
		}
	}
 }
 
function validaform7(){

	c_area_geo 			= $('textarea[name = c_area_geo]');
	c_estructura_dato 	= $('input[name = c_estructura_dato]');
	c_estructura 		= $('input[name = c_estructura_dato]').val();
	c_tipo_dato 		= $('input[name = c_tipo_dato]');
	c_total_datos		= $('input[name = c_total_datos]');
	c_oeste 			= $('input[name = c_oeste]');
	c_este 				= $('input[name = c_este]');
	c_norte 			= $('input[name = c_norte]');
	c_sur 				= $('input[name = c_sur]');
	
	c_id_proyeccion 	= $('input[name = c_id_proyeccion]');
	c_datum				= $('input[name = c_datum]');
	c_elipsoide 		= $('input[name = c_elipsoide]');
	
	r_nun_renglones 	= $('input[name = r_nun_renglones]');
	r_num_columnas		= $('input[name = r_num_columnas]');
	r_pixel_X 			= $('input[name = r_pixel_X]');
	r_pixel_Y			= $('input[name = r_pixel_Y]');
	r_COOR_X			= $('input[name = r_COOR_X]');
	r_COOR_Y			= $('input[name = r_COOR_Y]');
		
	var validaDatos = [	[c_area_geo 		, " Ingrese la descripción del área geográfica"	],
						[c_estructura_dato	, " Ingrese la estructura del dato"				],
						[c_tipo_dato		, " Ingrese el tipo de dato"					],
						[c_total_datos		, " Ingrese el número total de datos"			],
						[c_oeste			, " Ingrese las coordenadas del extremo oeste"] ,
						[c_este				, " Ingrese las coordenadas del extremo este"] ,
						[c_norte			, " Ingrese las coordenadas del extremo norte"] ,
						[c_sur				, " Ingrese las coordenadas del extremo sur"]
						
					];
	
	var validaProyeccion = [ 	[c_id_proyeccion, " Ingrese la proyección cartográfica"] , 
								[c_datum		 , " Ingrese el datum"] ,
								[c_elipsoide	 , " Ingrese el nombre del elipsoide"] 							
							];
	
	var validaRaster = [ 	[r_nun_renglones, " Ingrese el número de renglones"] ,
							[r_num_columnas,  " Ingrese el número de columnas"] ,
							[r_pixel_X		, " Ingrese el tamaño del pixel X"] ,
							[r_pixel_Y		, " Ingrese el tamaño del pixel Y"] ,
							[r_COOR_X		, " Ingrese la coordenada X"] ,
							[r_COOR_Y		, " Ingrese la coordenada Y"] 								
						];
						
	for (i = 0 ; i <= validaDatos.length ; i++){
		if (typeof(validaDatos[i]) !== 'undefined'){			
			if (validaDatos[i][0].val() == '' || validaDatos[i][0].val() == 0) {
					createAlert (validaDatos[i][0] , validaDatos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaDatos[i][0]);	}
		}
	}
	
	if (c_estructura == "Vector" || c_estructura == "vector")
	{
		for (i = 0 ; i <= validaProyeccion.length ; i++){
			if (typeof(validaProyeccion[i]) !== 'undefined'){			
				if (validaProyeccion[i][0].val() == '' || validaProyeccion[i][0].val() == 0) {
						createAlert (validaProyeccion[i][0] , validaProyeccion[i][1]);
						return false;
						break;
					}
					
				else{	removeAlert (validaProyeccion[i][0]);	}
			}
		}
		
	}
	
	if (c_estructura == "Raster" || c_estructura == "raster" || c_estructura == "RASTER")
	{
		for (i = 0 ; i <= validaRaster.length ; i++){
			if (typeof(validaRaster[i]) !== 'undefined'){			
				if (validaRaster[i][0].val() == '' || validaRaster[i][0].val() == 0) {
						createAlert (validaRaster[i][0] , validaRaster[i][1]);
						return false;
						break;
				}
						
				else{	removeAlert (validaRaster[i][0]);	}
			}
		}
	}// fin if
 }
 
function validaform8(){

	c_tabla					= $('input[name = c_tabla]');
	c_descrip_tabla			= $('textarea[name = c_descrip_tabla]');
	a_nombre 				= $('input[name ^= a_nombre]');
	a_descipcion_atributo 	= $('input[name ^= a_descipcion_atributo]');
	a_fuente				= $('input[name ^= a_fuente]');
	a_unidades 				= $('input[name ^= a_unidades]');
	a_tipo 					= $('input[name ^= a_tipo]');

		
	var validaAtributos = [ 	[c_tabla				, " Ingrese el nombre de la entidad"] , 
								[c_descrip_tabla		, " Ingrese la descripción de la entidad"] ,
								[a_nombre	 			, " Ingrese el nombre del atributo"] ,
								[a_descipcion_atributo	, " Ingrese la descripción del atributo"] , 
								[a_fuente		 		, " Ingrese la fuente del atributo"] ,
								[a_unidades	 			, " Ingrese las unidades de medida"] 	,
								[a_tipo	 				, " Ingrese el tipo de dato"] 												
						];
						
	for (i = 0 ; i <= validaAtributos.length ; i++){
		if (typeof(validaAtributos[i]) !== 'undefined'){			
			if (validaAtributos[i][0].val() == '' || validaAtributos[i][0].val() == 0) {
					createAlert (validaAtributos[i][0] , validaAtributos[i][1]);
					return false;
					break;
				}
				
			else{	removeAlert (validaAtributos[i][0]);	}
		}
	}
	
 }
 
function validaMetadato(){

	delete_metadato		= $('input[name = delete_metadato]');
	seleccion			= $('select[name = seleccion]');
	
	if (delete_metadato.val() == '' || delete_metadato.val() == 0) {
		createAlert (seleccion , "Seleccione un metadato");
		return false;
	}
				
	else{	removeAlert (seleccion);	}
  }

function createAlert(campo, txt) {
	campo.addClass('highlight');
	$('.error').replaceWith('<div id="validaError" class="error" >'+txt+'</div>');
	$('.error').fadeIn('slow');
	$(".error").fadeOut(9000);
	$(campo).focus();
	
	return false;
 }

function removeAlert(campo) {
	campo.removeClass('highlight');
	$('.error').hide();
 }

function verTablas(cv_principal ,clave){

	tablas = ["divAutores" , "ligas", "divTemas" , "divSitios"];
	for (i = 0 ; i <= tablas.length ; i++)
		{	if (typeof(tablas[i]) !== 'undefined'){	generaTablas(clave , tablas [i] , cv_principal);		}	}
 }	
 
function generaTablas(clave , caso ,idMetadato ){
var dataString = {var1 : clave , var3: caso , var4: idMetadato}
	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "html",
		success: function(data){
				
				$('#'+caso+'').html(data);			
			}
		});
 }
 
function generaList(clave, id) {
	
	$('option', '#seleccion').remove();
	$('#seleccion').append('<option value="0">Seleccione un metadato</option>');
	
	var dataString = {var1 : clave , var3: "list", var4: 1}
	
	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			if( result.success ) {
				var output = ""+result.numRows+"";
				$.each(result.listId, function (key, value) { 
					if(value['record_id'] == id)  {		$('#seleccion').append('<option selected value='+value['record_id']+'>'+value['nombre']+'</option>');}
					else  {		$('#seleccion').append('<option value='+value['record_id']+'>'+value['nombre']+'</option>');}
				});  
			}
		});
 }
 
function generaArbol(clave , caso ,idMetadato ) {
	
	var dataArbol = {var1 : clave , var3: "divClasificacion" , var4: idMetadato}
	
	$.ajax({
		data: dataArbol,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			if( result.success ) {
				var output = ""+result.numRows+"";
				$.each(result.listClas, function (key, value) { 
					$('#c_clasificacion').val(value['clasificacion']);
					collapseAll();
					expandTo(value['clasificacion']);
				});  
				
			}
		});
 }
	
function generaEdos(cv_principal) {
	var dataString = {cv_principal : cv_principal , var3: "estado"}
	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			$.each(result.listId, function (key, value) { 
				if(result.edo != 0){
					if(value['cve_ent'] == result.id_edo){ 	
						$('#estado').append('<option selected value='+value['cve_ent']+'>'+value['nom_ent']+'</option>');	
						if(value['cve_ent'] == '33'){$("#otro").show(); }	
						else{$("#otro").hide(); }	
						
					}
					else{
						$('#estado').append('<option value='+value['cve_ent']+'>'+value['nom_ent']+'</option>');				
					}
				}
				else{	$('#estado').append('<option value='+value['cve_ent']+'>'+value['nom_ent']+'</option>');			
						//$('#c_pubplace').val('');
						
					}
				
			});  
		});
 }

function generaMunis(id_edo , cv_principal){

 	var dataString = {id_edo: id_edo , cv_principal:cv_principal , var3: "municipio"}
	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
				
				$('option', '#municipio').remove();
				$('option', '#localidad').remove();
				$('#municipio').append('<option value="0">Seleccione un municipio</option>');
				$('#localidad').append('<option value="0">Seleccione una localidad</option>');
				
				$.each(result.listId, function (key, value) { 
					if(result.idMuni != 0){
						if(value['cve_mun'] == result.idMuni){	$('#municipio').append('<option selected value='+value['cve_mun']+'>'+value['nom_mun']+'</option>');}
						else{									$('#municipio').append('<option value='+value['cve_mun']+'>'+value['nom_mun']+'</option>');			}
					}
					else{	$('#municipio').append('<option value='+value['cve_mun']+'>'+value['nom_mun']+'</option>');			}
				});			
		});
  }

function generaLoc(id_mun , cv_principal){
	
 	var dataString = {id_mun : id_mun , cv_principal: cv_principal , var3: "localidad"}
	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {

			$('option', '#localidad').remove();
			$('#localidad').append('<option value="0">Seleccione una localidad</option>');
				
			$.each(result.listId, function (key, value) { 
				if(result.idLoc != 0){
					if(value['cve_loc'] == result.idLoc){	$('#localidad').append('<option selected value='+value['cve_loc']+'>'+value['nom_loc']+'</option>');}
					else{									$('#localidad').append('<option value='+value['cve_loc']+'>'+value['nom_loc']+'</option>');}
				}
				else{	$('#localidad').append('<option value='+value['cve_loc']+'>'+value['nom_loc']+'</option>');			}
			});  

		});
 }
 
function vectores(nameMetadato) {
	
	var archivoTxt = $("#userfile").val();
	var fileName = archivoTxt.split("\\");
	var fileName = fileName[fileName.length-1];
	var dataString = {metadato : nameMetadato , fileMetadato : fileName , contenido : "vectores"}

		$.ajax({
		data: dataString,
		url: 'php/subir2.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
				$("#c_datum").val(result.datumName);
				$("#c_tipo_dato").val(result.geometry);
				$("#c_total_datos").val(result.count);
				$("#c_elipsoide").val(result.geogcssName);	
				$("#c_oeste").val(result.Xmin); 
				$("#c_sur").val(result.Ymin);
				$("#c_este").val(result.Xmax);
				$("#c_norte").val(result.Ymax);
				$("#c_id_proyeccion").val(result.proyeccion);
				
				if((result.geometry == 'Point') || (result.geometry =='Polygon') || (result.geometry =='Line String'))
				{
					$("#c_estructura_dato").val("Vector");
				}
				$("#r_num_columnas").val("");
				$("#r_nun_renglones").val("");
				$("#r_pixel_X").val("");
				$("#r_pixel_Y").val("");
				$("#r_COOR_X").val("");
				$("#r_COOR_Y").val("");			
			
		});
 }
 
function archivosTif(nameTif) {
	var archivoTxt = $("#userfile").val();
	var fileName = archivoTxt.split("\\");
	var fileName = fileName[fileName.length-1];
	var dataString = {metadato : nameTif , fileMetadato : fileName, contenido : "tif"}

		$.ajax({
		data: dataString,
		url: 'php/subir2.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
				$("#c_datum").val("");
				$("#c_estructura_dato").val("Raster");
				$("#c_tipo_dato").val(result.tifDato);
				$("#c_total_datos").val(result.count);
				$("#c_elipsoide").val("");	
				$("#c_oeste").val(result.Xmin); 
				$("#c_sur").val(result.Ymin);
				$("#c_este").val(result.Xmax);
				$("#c_norte").val(result.Ymax);
				$("#c_id_proyeccion").val("");
				$("#r_num_columnas").val(result.tifColunmas);
				$("#r_nun_renglones").val(result.tifRenglones);
				$("#r_pixel_X").val(result.tifPixelX);
				$("#r_pixel_Y").val(result.tifPixelY);
				$("#r_COOR_X").val(result.tifRasterX);
				$("#r_COOR_Y").val(result.tifRasterY);
		});
 }

function limpiarGeoinfo (){
	$("#c_datum").val("");
	$("#c_tipo_dato").val("");
	$("#c_total_datos").val("");
	$("#c_elipsoide").val("");	
	$("#c_oeste").val(""); 
	$("#c_sur").val("");
	$("#c_este").val("");
	$("#c_norte").val("");
	$("#c_id_proyeccion").val("");
	$("#c_estructura_dato").val("");
	$("#r_num_columnas").val("");
	$("#r_nun_renglones").val("");
	$("#r_pixel_X").val("");
	$("#r_pixel_Y").val("");
	$("#r_COOR_X").val("");
	$("#r_COOR_Y").val("");		
 }

 function close_inputs_metadato(){
 	$('#reabrir-metadato').prop('disabled', false);
	$('#cerrar-metadato').prop('disabled', true);
	$('#id_metadato1').prop('disabled', true);
	$('#id_metadato2').prop('disabled', true);
	$('#id_metadato3').prop('disabled', true);
	$('#id_metadato4').prop('disabled', true);
	$('#id_metadato5').prop('disabled', true);
	$('#id_metadato6').prop('disabled', true);
	$('#id_metadato7').prop('disabled', true);
	$('#id_metadato8').prop('disabled', true);
	$('#id_metadato9').prop('disabled', true);
	$('#nuevoM').prop('disabled', true);
	$('#deleteM').prop('disabled', true);
	$('#c_nombre').prop('disabled', true);
	$('#c_cobertura').prop('disabled', true);
	$('#c_fecha_inicial').prop('disabled', true);
	$('#c_fecha').prop('disabled', true);
	$('#c_version').prop('disabled', true);
	$('#c_publish').prop('disabled', true);
	$('#c_publish_siglas').prop('disabled', true);
	$('#c_pubplace').prop('disabled', true);
	$('#c_pubdate').prop('disabled', true);
	$('#c_edition').prop('disabled', true);
	$('#c_escala').prop('disabled', true);
	$('#c_clave').prop('disabled', true);
	$('#c_avance').prop('disabled', true);
	$('#c_mantenimiento').prop('disabled', true);
	$('#c_issue').prop('disabled', true);
	$('#c_resumen').prop('disabled', true);
	$('#c_abstract').prop('disabled', true);
	$('#c_objetivo').prop('disabled', true);
	$('#c_datos_comp').prop('disabled', true);
	$('#c_tamano').prop('disabled', true);
	$('#c_geoform').prop('disabled', true);
	$('#c_tiempo').prop('disabled', true);
	$('#c_tiempo2').prop('disabled', true);
	$('#c_acceso').prop('disabled', true);
	$('#c_uso').prop('disabled', true);
	$('#c_observaciones').prop('disabled', true);
	$('#c_software_hardware').prop('disabled', true);
	$('#c_sistema_operativo').prop('disabled', true);
	$('#c_tecnicos').prop('disabled', true);
	$('#c_path').prop('disabled', true);
	$('#c_metodologia').prop('disabled', true);
	$('#c_descrip_metodologia').prop('disabled', true);
	$('#c_descrip_proceso').prop('disabled', true);
	$('#c_estructura_dato').prop('disabled', true);
	$('#c_tipo_dato').prop('disabled', true);
	$('#c_total_datos').prop('disabled', true);
	$('#c_id_proyeccion').prop('disabled', true);
	$('#c_datum').prop('disabled', true);
	$('#c_elipsoide').prop('disabled', true);
	$('#c_area_geo').prop('disabled', true);
	$('#c_oeste').prop('disabled', true);
	$('#c_este').prop('disabled', true);
	$('#c_norte').prop('disabled', true);
	$('#c_sur').prop('disabled', true);
	$('#c_tabla').prop('disabled', true);
	$('#c_descrip_tabla').prop('disabled', true);
	$('#estado').prop('disabled', true);
	$('#municipio').prop('disabled', true);
	$('#localidad').prop('disabled', true);
	$('.clsAnchoTotal').each(function(){
		$(this).prop('disabled', true);
	});
	$('.clsAgregarFila').each(function(){
		$(this).prop('disabled', true);
	});
	$('.clsEliminarFila').each(function(){
		$(this).prop('disabled', true);
	});
	$('#form1').prop('disabled', true);
	$('#x_origin').prop('disabled', true);
 }

 function open_inputs_metadato(){
 	$('#reabrir-metadato').prop('disabled', true);
	$('#cerrar-metadato').prop('disabled', false);
	$('#id_metadato1').prop('disabled', false);
	$('#id_metadato2').prop('disabled', false);
	$('#id_metadato3').prop('disabled', false);
	$('#id_metadato4').prop('disabled', false);
	$('#id_metadato5').prop('disabled', false);
	$('#id_metadato6').prop('disabled', false);
	$('#id_metadato7').prop('disabled', false);
	$('#id_metadato8').prop('disabled', false);
	$('#id_metadato9').prop('disabled', false);
	$('#nuevoM').prop('disabled', false);
	$('#deleteM').prop('disabled', false);
	$('#c_nombre').prop('disabled', false);
	$('#c_cobertura').prop('disabled', false);
	$('#c_fecha_inicial').prop('disabled', false);
	$('#c_fecha').prop('disabled', false);
	$('#c_version').prop('disabled', false);
	$('#c_publish').prop('disabled', false);
	$('#c_publish_siglas').prop('disabled', false);
	$('#c_pubplace').prop('disabled', false);
	$('#c_pubdate').prop('disabled', false);
	$('#c_edition').prop('disabled', false);
	$('#c_escala').prop('disabled', false);
	$('#c_clave').prop('disabled', false);
	$('#c_avance').prop('disabled', false);
	$('#c_mantenimiento').prop('disabled', false);
	$('#c_issue').prop('disabled', false);
	$('#c_resumen').prop('disabled', false);
	$('#c_abstract').prop('disabled', false);
	$('#c_objetivo').prop('disabled', false);
	$('#c_datos_comp').prop('disabled', false);
	$('#c_tamano').prop('disabled', false);
	$('#c_geoform').prop('disabled', false);
	$('#c_tiempo').prop('disabled', false);
	$('#c_tiempo2').prop('disabled', false);
	$('#c_acceso').prop('disabled', false);
	$('#c_uso').prop('disabled', false);
	$('#c_observaciones').prop('disabled', false);
	$('#c_software_hardware').prop('disabled', false);
	$('#c_sistema_operativo').prop('disabled', false);
	$('#c_tecnicos').prop('disabled', false);
	$('#c_path').prop('disabled', false);
	$('#c_metodologia').prop('disabled', false);
	$('#c_descrip_metodologia').prop('disabled', false);
	$('#c_descrip_proceso').prop('disabled', false);
	$('#c_estructura_dato').prop('disabled', false);
	$('#c_tipo_dato').prop('disabled', false);
	$('#c_total_datos').prop('disabled', false);
	$('#c_id_proyeccion').prop('disabled', false);
	$('#c_datum').prop('disabled', false);
	$('#c_elipsoide').prop('disabled', false);
	$('#c_area_geo').prop('disabled', false);
	$('#c_oeste').prop('disabled', false);
	$('#c_este').prop('disabled', false);
	$('#c_norte').prop('disabled', false);
	$('#c_sur').prop('disabled', false);
	$('#c_tabla').prop('disabled', false);
	$('#c_descrip_tabla').prop('disabled', false);
	$('#estado').prop('disabled', false);
	$('#municipio').prop('disabled', false);
	$('#localidad').prop('disabled', false);
	$('.clsAnchoTotal').each(function(){
		$(this).prop('disabled', false);
	});
	$('.clsAgregarFila').each(function(){
		$(this).prop('disabled', false);
	});
	$('.clsEliminarFila').each(function(){
		$(this).prop('disabled', false);
	});
	$('#form1').prop('disabled', false);
	$('#x_origin').prop('disabled', false);
 }
 
function metadato(idMetadato , clave) {
	$(location).attr('hash',idMetadato); 

	dataString = {var1 : clave , var3: "values" , var4: idMetadato}

	$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			if( result.success ) {
				var output = ""+result.success+"";
				$.each(result.coberturas, function (key, value) { 
					$('#reabrir-metadato-hidden').val(value['record_id']);
					$('#cerrar-metadato-hidden').val(value['record_id']);
					$('#id_metadato1').val(value['record_id']);
					$('#id_metadato2').val(value['record_id']);
					$('#id_metadato3').val(value['record_id']);
					$('#id_metadato4').val(value['record_id']);
					$('#id_metadato5').val(value['record_id']);
					$('#id_metadato6').val(value['record_id']);
					$('#id_metadato7').val(value['record_id']);
					$('#id_metadato8').val(value['record_id']);
					$('#id_metadato9').val(value['record_id']);
					$('#nuevoM').val(value['record_id']);
					$('#deleteM').val(value['record_id']);
					$('#c_nombre').val(value['nombre']);
					$('#c_cobertura').val(value['cobertura']);
					$('#c_fecha_inicial').val(value['fecha_inicial']);
					$('#c_fecha').val(value['fecha']);
					$('#c_version').val(value['version']);
					$('#c_publish').val(value['publish']);
					$('#c_publish_siglas').val(value['publish_siglas']);
					$('#c_pubplace').val(value['pubplace']);
					$('#c_pubdate').val(value['pubdate']);
					$('#c_edition').val(value['edition']);
					$('#c_escala').val(value['escala']);
					$('#c_clave').val(value['clave']);
					$('#c_avance').val(value['avance']);
					$('#c_mantenimiento').val(value['mantenimiento']);
					$('#c_issue').val(value['issue']);
					$('#c_resumen').val(value['resumen']);
					$('#c_abstract').val(value['abstract']);
					$('#c_objetivo').val(value['objetivo']);
					$('#c_datos_comp').val(value['datos_comp']);
					$('#c_tamano').val(value['tamano']);
					$('#c_geoform').val(value['geoform']);
					$('#c_tiempo').val(value['tiempo']);
					$('#c_tiempo2').val(value['tiempo2']);
					$('#c_acceso').val(value['acceso']);
					$('#c_uso').val(value['uso']);
					$('#c_observaciones').val(value['observaciones']);
					$('#c_software_hardware').val(value['software_hardware']);
					$('#c_sistema_operativo').val(value['sistema_operativo']);
					$('#c_tecnicos').val(value['tecnicos']);
					$('#c_path').val(value['path']);
					$('#c_metodologia').val(value['metodologia']);
					$('#c_descrip_metodologia').val(value['descrip_metodologia']);
					$('#c_descrip_proceso').val(value['descrip_proceso']);
					$('#c_estructura_dato').val(value['estructura_dato']);
					$('#c_tipo_dato').val(value['tipo_dato']);
					$('#c_total_datos').val(value['total_datos']);
					$('#c_id_proyeccion').val(value['id_proyeccion']);
					$('#c_datum').val(value['datum']);
					$('#c_elipsoide').val(value['elipsoide']);
					$('#c_area_geo').val(value['area_geo']);
					$('#c_oeste').val(value['oeste']);
					$('#c_este').val(value['este']);
					$('#c_norte').val(value['norte']);
					$('#c_sur').val(value['sur']);
					$('#c_tabla').val(value['tabla']);
					$('#c_descrip_tabla').val(value['descrip_tabla']);

					//console.log(value['abierto']);

					if(value['abierto'] !== 'f'){
						$('#reabrir-metadato').prop('disabled', true);
						$('#cerrar-metadato').prop('disabled', false);
						$('#id_metadato1').prop('disabled', false);
						$('#id_metadato2').prop('disabled', false);
						$('#id_metadato3').prop('disabled', false);
						$('#id_metadato4').prop('disabled', false);
						$('#id_metadato5').prop('disabled', false);
						$('#id_metadato6').prop('disabled', false);
						$('#id_metadato7').prop('disabled', false);
						$('#id_metadato8').prop('disabled', false);
						$('#id_metadato9').prop('disabled', false);
						$('#nuevoM').prop('disabled', false);
						$('#deleteM').prop('disabled', false);
						$('#c_nombre').prop('disabled', false);
						$('#c_cobertura').prop('disabled', false);
						$('#c_fecha_inicial').prop('disabled', false);
						$('#c_fecha').prop('disabled', false);
						$('#c_version').prop('disabled', false);
						$('#c_publish').prop('disabled', false);
						$('#c_publish_siglas').prop('disabled', false);
						$('#c_pubplace').prop('disabled', false);
						$('#c_pubdate').prop('disabled', false);
						$('#c_edition').prop('disabled', false);
						$('#c_escala').prop('disabled', false);
						$('#c_clave').prop('disabled', false);
						$('#c_avance').prop('disabled', false);
						$('#c_mantenimiento').prop('disabled', false);
						$('#c_issue').prop('disabled', false);
						$('#c_resumen').prop('disabled', false);
						$('#c_abstract').prop('disabled', false);
						$('#c_objetivo').prop('disabled', false);
						$('#c_datos_comp').prop('disabled', false);
						$('#c_tamano').prop('disabled', false);
						$('#c_geoform').prop('disabled', false);
						$('#c_tiempo').prop('disabled', false);
						$('#c_tiempo2').prop('disabled', false);
						$('#c_acceso').prop('disabled', false);
						$('#c_uso').prop('disabled', false);
						$('#c_observaciones').prop('disabled', false);
						$('#c_software_hardware').prop('disabled', false);
						$('#c_sistema_operativo').prop('disabled', false);
						$('#c_tecnicos').prop('disabled', false);
						$('#c_path').prop('disabled', false);
						$('#c_metodologia').prop('disabled', false);
						$('#c_descrip_metodologia').prop('disabled', false);
						$('#c_descrip_proceso').prop('disabled', false);
						$('#c_estructura_dato').prop('disabled', false);
						$('#c_tipo_dato').prop('disabled', false);
						$('#c_total_datos').prop('disabled', false);
						$('#c_id_proyeccion').prop('disabled', false);
						$('#c_datum').prop('disabled', false);
						$('#c_elipsoide').prop('disabled', false);
						$('#c_area_geo').prop('disabled', false);
						$('#c_oeste').prop('disabled', false);
						$('#c_este').prop('disabled', false);
						$('#c_norte').prop('disabled', false);
						$('#c_sur').prop('disabled', false);
						$('#c_tabla').prop('disabled', false);
						$('#c_descrip_tabla').prop('disabled', false);
						$('#estado').prop('disabled', false);
						$('#municipio').prop('disabled', false);
						$('#localidad').prop('disabled', false);
						$('.clsAnchoTotal').each(function(){
							$(this).prop('disabled', false);
						});
						$('.clsAgregarFila').each(function(){
							$(this).prop('disabled', false);
						});
						$('.clsEliminarFila').each(function(){
							$(this).prop('disabled', false);
						});
						$('#form1').prop('disabled', false);
						$('#x_origin').prop('disabled', false);
					}else{
						$('#cerrar-metadato').prop('disabled', true);
						$('#reabrir-metadato').prop('disabled', false);
						$('#id_metadato1').prop('disabled', true);
						$('#id_metadato2').prop('disabled', true);
						$('#id_metadato3').prop('disabled', true);
						$('#id_metadato4').prop('disabled', true);
						$('#id_metadato5').prop('disabled', true);
						$('#id_metadato6').prop('disabled', true);
						$('#id_metadato7').prop('disabled', true);
						$('#id_metadato8').prop('disabled', true);
						$('#id_metadato9').prop('disabled', true);
						$('#nuevoM').prop('disabled', true);
						$('#deleteM').prop('disabled', true);
						$('#c_nombre').prop('disabled', true);
						$('#c_cobertura').prop('disabled', true);
						$('#c_fecha_inicial').prop('disabled', true);
						$('#c_fecha').prop('disabled', true);
						$('#c_version').prop('disabled', true);
						$('#c_publish').prop('disabled', true);
						$('#c_publish_siglas').prop('disabled', true);
						$('#c_pubplace').prop('disabled', true);
						$('#c_pubdate').prop('disabled', true);
						$('#c_edition').prop('disabled', true);
						$('#c_escala').prop('disabled', true);
						$('#c_clave').prop('disabled', true);
						$('#c_avance').prop('disabled', true);
						$('#c_mantenimiento').prop('disabled', true);
						$('#c_issue').prop('disabled', true);
						$('#c_resumen').prop('disabled', true);
						$('#c_abstract').prop('disabled', true);
						$('#c_objetivo').prop('disabled', true);
						$('#c_datos_comp').prop('disabled', true);
						$('#c_tamano').prop('disabled', true);
						$('#c_geoform').prop('disabled', true);
						$('#c_tiempo').prop('disabled', true);
						$('#c_tiempo2').prop('disabled', true);
						$('#c_acceso').prop('disabled', true);
						$('#c_uso').prop('disabled', true);
						$('#c_observaciones').prop('disabled', true);
						$('#c_software_hardware').prop('disabled', true);
						$('#c_sistema_operativo').prop('disabled', true);
						$('#c_tecnicos').prop('disabled', true);
						$('#c_path').prop('disabled', true);
						$('#c_metodologia').prop('disabled', true);
						$('#c_descrip_metodologia').prop('disabled', true);
						$('#c_descrip_proceso').prop('disabled', true);
						$('#c_estructura_dato').prop('disabled', true);
						$('#c_tipo_dato').prop('disabled', true);
						$('#c_total_datos').prop('disabled', true);
						$('#c_id_proyeccion').prop('disabled', true);
						$('#c_datum').prop('disabled', true);
						$('#c_elipsoide').prop('disabled', true);
						$('#c_area_geo').prop('disabled', true);
						$('#c_oeste').prop('disabled', true);
						$('#c_este').prop('disabled', true);
						$('#c_norte').prop('disabled', true);
						$('#c_sur').prop('disabled', true);
						$('#c_tabla').prop('disabled', true);
						$('#c_descrip_tabla').prop('disabled', true);
						$('#estado').prop('disabled', true);
						$('#municipio').prop('disabled', true);
						$('#localidad').prop('disabled', true);
						$('.clsAnchoTotal').each(function(){
							$(this).prop('disabled', true);
						});
						$('.clsAgregarFila').each(function(){
							$(this).prop('disabled', true);
						});
						$('.clsEliminarFila').each(function(){
							$(this).prop('disabled', true);
						});
						$('#form1').prop('disabled', true);
						$('#x_origin').prop('disabled', true);

					}
					
					if(value['estructura_dato'] == 'Raster' || value['estructura_dato'] == 'raster')
					{
						raster(idMetadato , clave);
					}
					else
					{
						$("#r_num_columnas").val("");
						$("#r_nun_renglones").val("");
						$("#r_pixel_X").val("");
						$("#r_pixel_Y").val("");
						$("#r_COOR_X").val("");
						$("#r_COOR_Y").val("");	
					}

				 }); 
			}
		});
	
 }
 
function raster(idMetadato , clave){
	dataRaster = {var1 : clave , var3: "raster" , var4: idMetadato}
	$.ajax({
		data: dataRaster,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			if( result.success ) {
				$.each(result.raster, function (key, value) { 
					$("#r_num_columnas").val(value['num_columnas']);
					$("#r_nun_renglones").val(value['nun_renglones']);
					$("#r_pixel_X").val(value['pixel_x']);
					$("#r_pixel_Y").val(value['pixel_y']);
					$("#r_COOR_X").val(value['coor_x']);
					$("#r_COOR_Y").val(value['coor_y']);
				 }); 
			}
		});
 }

function guardarAlert(txt) {
	
	$('.guardar').replaceWith('<div id="validaError" class="guardar">'+txt+'</div>');
	$('.guardar').fadeIn('slow');
	$(".guardar").fadeOut(8000);

 } 
 
function displayBlock(value) {
	
	for(i=1; i<=15;i++)
	{ 
		if (i == value)
		{
			$("#div"+i).css("display", "block");
		}
		else{
			$("#div"+i).css("display", "none");
		}
	} 

 } 
 
/*Ocultar Botones*/
$(document).ready(function(){
	$(".accordion h1:first").addClass("active");
    $(".accordion div:not(:first)").hide();
    $(".accordion h1").click(function()
	{
    	$(this).next("div").slideToggle("fast")
     	.siblings("div:visible").slideUp("fast");
    	$(this).toggleClass("active");
    	$(this).siblings("h1").removeClass("active");
    });
 });

/*Manipulación Tabla*/
//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA FILA---- >>>>>>>>>>>>>
$(document).on('click','.clsAgregarFila',function(){
	var objTabla=$(this).parents().get(3);
	var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	var strNueva_Fila='<tr>'+ node.innerHTML +	'</tr>';
	$(objTabla).find('tbody').append(strNueva_Fila);
	if(!$(objTabla).find('tbody').is(':visible')){
		$(objTabla).find('caption').click();
	}
 });

//<<<<<<<<<<<<<<<---- FUNCION QUE ELIMINA FILA---- >>>>>>>>>>>>>
$(document).on('click','.clsEliminarFila',function(){
	var objCuerpo=$(this).parents().get(2);
	if($(objCuerpo).find('tr').length==1)	{	return;	}
	else{
		var objFila=$(this).parents().get(1);
		$(objFila).remove();
	}
 });
 
 //<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA FILA EN LA <<<TABLA_D>>> DEL APARTADO DE CALIDAD DE LOS DATOS/ REFERENCIA DE LOS DATOS ORIGINALES---- >>>>>>>>>>>>>
$(document).on('click','.clsAgregarFilad',function(){
   		var objCuerpo=$(this).parents().get(2);
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
		var pos_letra = strNueva_Fila.indexOf('h_origina') + 9;
		var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 6).indexOf('"');
	    var letra = strNueva_Fila.substring( pos_letra, pos_letra + pos_a ) ;
		var letra1 = (letra * 1 ) + 1;
        do { 
          		strNueva_Fila = strNueva_Fila.replace('h_origina'+ letra ,'h_origina' + letra1 );
        	} 
		while(strNueva_Fila.indexOf('h_origina' + letra) >= 0);
		
		strNueva_Fila = strNueva_Fila.replace('d_idorigen[]" value="a'+ letra ,'d_idorigen[]" value="a' + letra1 );
  	    $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible'))
		{
			$(objTabla).find('caption').click();
		}
	});	


//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA LA FILA GENERAL EN LA <<<TABLA_T>>> DEL APARTADO DE TAXONOMIA---- >>>>>>>>>>>>>
$(document).on('click','.clsAgregarFilat',function(){
	var objCuerpo=$(this).parents().get(2);
	var objTabla=$(this).parents().get(3);
	var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
	 //alert(strNueva_Fila);
	var pos_letra = strNueva_Fila.indexOf('g_titlet') + 8;
	var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 6).indexOf('"');
	var letra = strNueva_Fila.substring( pos_letra, pos_letra + pos_a ) ;
	var letra1 = (letra * 1 ) + 1;
	// alert( letra + "....." + letra1);
    do { 
         strNueva_Fila = strNueva_Fila.replace('t' +  letra ,'t'+  letra1 );
       } while(strNueva_Fila.indexOf('t' +  letra) >= 0);
          
		do { 
         strNueva_Fila = strNueva_Fila.replace('value="t'+ letra , 'value="t' + letra1 );
        } while(strNueva_Fila.indexOf('value="t' +  letra) >= 0);
 		 // alert(strNueva_Fila);

 		//strNueva_Fila += '<tr></td>Bibliografía: <textarea></textarea></td></tr>';
  	    $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible')){
			$(objTabla).find('caption').click();
		}
	});


$(document).on('click','.add-tax',function(){
	console.log('ADD BIBLIO');
	var id_button = this.id.split('-')[1];
	//console.log(id_button);
	var biblio = $('#biblio-' + id_button).html();
	//console.log(biblio);
	var taxo = $('#taxo-' + id_button).html();
	console.log($(taxo).find('li:eq(2)').text().split(': ')[1]);
	var objCuerpo=$('#button_taxones').parents().get(2);
	var objTabla=$('#button_taxones').parents().get(3);
	var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	var strNueva_Fila='<tr>'+ node.innerHTML +	'</tr>';
	 //alert(strNueva_Fila);
	var pos_letra = strNueva_Fila.indexOf('g_titlet') + 8;
	var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 6).indexOf('"');
	var letra = strNueva_Fila.substring( pos_letra, pos_letra + pos_a ) ;
	var letra1 = (letra * 1 ) + 1;
	// alert( letra + "....." + letra1);
    do { 
         strNueva_Fila = strNueva_Fila.replace('t' +  letra ,'t'+  letra1 );
       } while(strNueva_Fila.indexOf('t' +  letra) >= 0);
          
		do { 
         strNueva_Fila = strNueva_Fila.replace('value="t'+ letra , 'value="t' + letra1 );
        } while(strNueva_Fila.indexOf('value="t' +  letra) >= 0);
 		 // alert(strNueva_Fila);

 		strNueva_Fila += '<tr><td>Bibliografía: '+biblio+'</td></tr>';
  	    $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible')){
			$(objTabla).find('caption').click();
		}

		//console.log($('#t_reino').html());
		//console.log($('#datosTaxon').html());

		var tax_tree = ['t_reino[]', 't_division[]',  't_clase[]', 't_orden[]', 't_familia[]', 't_genero[]', 't_especie[]', 't_nombrecomun[]']

		var tax_level = 0;
		tax_tree.forEach(name => {
			var index_last = -1;
			var ite = 0;
			$('.taxones').each(function(){
				console.log($(this).attr('name'));
				if($(this).attr('name') === name){	
					if(ite >= index_last) {
						index_last = ite;
					}
				}
				ite += 1;
			})
			var tax_value = $(taxo).find('li:eq('+tax_level+')').text().split(': ')[1];
			$('.taxones:eq('+index_last+')').val(tax_value);
			tax_level += 1;
		});
		

	});	
	
	
//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA LA FILA GENERAL EN LA <<<TABLA_TC>>> DEL APARTADO DE TAXONOMIA/CITAS---- >>>>>>>>>>>>>
$(document).on('click','.clsAgregarFilatc',function(){
   		var objCuerpo=$(this).parents().get(2);
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
		//   alert(strNueva_Fila);
		var pos_letra = strNueva_Fila.indexOf('z_origin') + 8;
		var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 20).indexOf('_');
	    var idt = strNueva_Fila.substring( pos_letra, pos_letra + pos_a); 
		var letra = strNueva_Fila.substring( pos_letra + pos_a + 2, pos_letra + pos_a + 3 ) ;
		var letra1 = (letra * 1 ) + 1;
         // alert(letra1);
        do { 
          strNueva_Fila = strNueva_Fila.replace('z_origin'+ idt + "_a"  +  letra ,'z_origin' + idt + "_a"  +  letra1 );
        } while(strNueva_Fila.indexOf('z_origin' + idt + "_a"  +  letra) >= 0);
          strNueva_Fila = strNueva_Fila.replace('value="a'+ letra , 'value="a' + letra1 );
         // alert(strNueva_Fila);
  	      $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible')){
			$(objTabla).find('caption').click();
		}
	});	
	
var cambiar = {
   	accion:  function(show){ this.ocultar(); this.mostrar(show); },
   	ocultar: function()
	{ 
		var divs = document.getElementsByTagName('div');
		for(i=1; i<=15;i++)
		{ 
			if (document.getElementById("div"+i).style.display == "block")
			{
				document.getElementById("div"+i).style.display = "none";
			}
		} 
	},
   	mostrar: function(num){ console.log(num); document.getElementById("div"+num).style['display'] = "block"; }
 };
 
/*Scripts Arbol*/
function collapseAll(){
			$('#tt').tree('collapseAll');
		}
function expandAll(){
			$('#tt').tree('expandAll');
		}
function expandTo(idv){
			var node = $('#tt').tree('find',idv);
			$('#tt').tree('expandTo', node.target).tree('select', node.target);
			document.getElementsByName("c_clasif_ruta")[0].value =  getDirPath();
		}	
function getSelected(){
			var node = $('#tt').tree('getSelected');
			if (node){
			 var s = node.id;
			 document.getElementsByName("c_clasificacion")[0].value = s;
			 document.getElementsByName("c_clasif_ruta")[0].value =  getDirPath();
		  }
		}		 
function getDirPath(){
    var nodo = $('#tt').tree('getSelected');
    var path;
   
     while(undefined!=nodo){
        if(undefined!=nodo.id){
          path+="@"+nodo.text;
        }
        var nodeP=$('#tt').tree('getParent',nodo.target);
          nodo=nodeP;
      }
      var va=path.split('@');
          path="";
      for(var i=va.length-1;i>=0;i--){
          path+="\\"+va[i];
      }
      path=path.replace('@','');
      path=path.replace('\\undefined','');
	  path=path.replace('\\undefined','');
	  path=path.replace('\\Acervo','Acervo');
	  path=path.replace('<a href="#" style="text-decoration:none;color:black;" ondblclick="getSelected()">','')
 	  path=path.replace('</a>','')
     return path; 
   }
 
</script>

</head>

<body>
 
	<div id="hd">
    	<table>
          <tr>
            <td width="20%"><img src="img/conabio_03.png"></td>
            <td><span>
			  <p class="txtN1"> Direcci&oacute;n General de Geom&aacute;tica</p>
              <p class="txtN2">Subcoordinaci&oacute;n de Sistemas de Informaci&oacute;n Geogr&aacute;fica</p>
			</span></td>
          </tr>
        </table>
	</div> <!-- FIN <div id="hd">-->
    <div id="nu">Bienvenido <b><?php echo $nombreUsuario.' '.$cv_principal; ?></b></div>
    <div id="cn">
		<div id="lf">
	    	<div id="lf1">
	    		<?php 
	    			if (in_array("8f0967f5-e5d4-4a1c-a60a-15a54a0fefad", $permissions)){
	    				echo '<input type="button" id="nuevo" value="Crear metadato">';
	    			}
	    		?>
	    		<?php
	    			if(in_array("8f0967f5-e5d4-4a1c-a60a-15a54a0fefad", $permissions)){
	    				echo '<input type="button" id="duplica" value="Duplicar metadato">';
	    			}
	    		?>
                <input type="button" id="borrarMetadato" value="Eliminar metadato">
                <input type="button" id="cerrarSesion" value="Cerrar sesi&oacute;n">
                 
	      	</div> 
          	<div id="lf2" class="accordion">

          		<?php 

          			if(in_array("f8735458-0e55-4097-b35b-73475fb4810d", $permissions) || in_array("f60cd433-781d-4768-b97b-ea5fedf7c3c8", $permissions)){
          				echo '<p><img src="img/vineta.png">Seleccione el registro a editar o revisar</p>';
          				echo '<select name= "seleccion" id= "seleccion" >
                				<option value="">Seleccione el metadato</option>
                			 </select>';
          			}

          		?>
            	<h1> Informaci&oacute;n b&aacute;sica </h1>
				<div>
                  <input type="button" onclick="cambiar.accion (1)" value="Datos generales">
                  <input type="button" onclick="cambiar.accion (2)" value="Restricciones">
                  <input type="button" onclick="cambiar.accion (3)" value="Palabras clave">
                  <input type="button" onclick="cambiar.accion (4)" value="Ambiente de trabajo">
				</div>
			  <h1>Calidad de los datos</h1>
				<div style="display:none;">
                  <input type="button" onclick="cambiar.accion (5)" value="Calidad de los datos">
                  <input type="button" onclick="cambiar.accion (6)" value="Taxonom&iacute;a">
                  <input type="button" onclick="cambiar.accion (15)" value="Consultar Taxonom&iacute;a">
				</div>
			  <h1> Informaci&oacute;n espacial y atributos</h1>
				<div style="display:none;">
                	
                  <input type="button" onclick="cambiar.accion (7)" value="Datos  espaciales">
                  <input type="button" onclick="cambiar.accion (8)" value="Atributos">
                  <input type="button" onclick="cambiar.accion (9)" value="Clasificaci&oacute;n y analista">                  	
				</div>	
                
               <h1> Herramientas de an&aacute;lisis</h1>
               <div style="display:none;">
                  <input type="button" onclick="cambiar.accion (10)" value="Archivo ejecutable">            	
				</div>

				<h1> Administraci&oacute;n de Usuarios</h1>
               	<div style="display:none;">
               		
               		<?php 
		    			if (in_array("1c3cd794-4527-4c68-b968-471b7cb9d197", $permissions)){
		    				echo '<input type="button" onclick="cambiar.accion (11)" value="Registrar Capturista">';
		    			}
		    		?>
		    		<?php 
		    			if (in_array("8faac4a9-7bc3-4c32-a5c9-05df93565dd5", $permissions)){
		    				echo '<input type="button" onclick="cambiar.accion (12)" value="Registrar Analista">';
		    			}
		    		?>
		    		<?php 
		    			if (in_array("8faac4a9-7bc3-4c32-a5c9-05df93565dd5", $permissions)){
		    				echo '<input type="button" onclick="cambiar.accion (13)" value="Registrar Administrador">';
		    			}
		    		?>
		    		<?php 
		    			if (in_array("8faac4a9-7bc3-4c32-a5c9-05df93565dd5", $permissions)) {
		    				echo '<input type="button" onclick="cambiar.accion (14)" value="Borrar Usuario">';
		    			}
		    		?>
				</div>	
               			
	      	</div> <!--FIN <div id="lf2" class="accordion">-->
        </div> <!--FIN <div id="lf">-->       
	</div> <!--FIN <div id="cn">-->
    <div id="validaError" class="error" ></div>
    <div id="validaError" class="guardar" ></div>
    
<div id="rg" > 
	<div   id="div1"  class="element" style="display:block " class="element">
    
    	<form name="datos" method="post" action='php/guardar.php?hoja=datos&cv_principal=<?php echo $cv_principal;?>' id="datos">
        	<input type="button" value = "Guardar"  id="form1" />
            		<input type="hidden" name="id_metadato"   id="id_metadato1" />
                    <input type="hidden" name="displayBlock"   value="1" />
             		<div id="autores"><?php  tabla("divAutores" ,"Autores:" , "x_origin",  'autores' ,0 , 0, 0 ); ?>    </div>


            <div class="row">
            	<div class="col-lg-6" style="text-align: right;">
            		<br>
            		<?php

            			if(in_array('0ae3aa86-62f6-4657-a545-66969f1a7ad2', $permissions)){
            				echo '<button type="button" id="cerrar-metadato" disabled="true">Cerrar metadato</button>';
            			}

            		?>	
            		<br>
            	</div>
            	<div class="col-lg-6" style="text-align: left;">
            		<br>
            		<?php

            			if(in_array('1e8f7036-9c86-4cbf-95b7-db96b7fe22fc', $permissions)) {
            				echo '<button type="button" id="reabrir-metadato" disabled="true">Reabrir metadato</button>';
            			}

            		?>
            		<br>
            	</div>
            </div>

            <br>


            <table width="870" border="0">
              	<tr>
                	<td width="195">T&iacute;tulo del mapa:</td>
                	<td colspan="5"><?php generaCampos("c_nombre","text","Título del mapa"); ?></td>
              	</tr>
              	<tr>
                	<td>Nombre del archivo:</td>
                	<td colspan="5"><?php generaCampos("c_cobertura","text","Nombre del dato geoespacial o capa digital"); ?></td>
  				</tr>
              	<tr>
                	<td>Fecha de ingreso:</td>
                	<td width="134"><?php generaCampos("c_fecha_inicial","fecha","Fecha de captura de metadato"); ?></td>
                	<td width="169">Fecha de actualizaci&oacute;n: </td>
                	<td width="120"><?php generaCampos("c_fecha","fecha",""); ?></td>
                	<td width="106">Versi&oacute;n FGDC:</td>
                	<td width="120"><?php generaCampos("c_version","text",""); ?></td>
              	</tr>
              	<tr>
                	<td colspan="6"><h3>Cita de la informaci&oacute;n</h3></td>
              	</tr>
              	<tr>
                	<td>Instituci&oacute;n responsable:</td>
                	<td colspan="5"><?php generaCampos("c_publish","text",""); ?></td>
              	</tr>
              	<tr>
                	<td>Siglas de la instituci&oacute;n:</td>
                	<td colspan="5"><?php generaCampos("c_publish_siglas","text",""); ?></td>
              	</tr>
			</table>
            <table width="870" border="0">
           		<tr>
               		<td width="195" rowspan="3">Lugar de publicación:</td>
                	<td colspan="2">Estado:</td>
                	<td width="29">&nbsp;</td>
               	  	<td colspan="2">Municipio:</td>
                	<td width="33">&nbsp;</td>
               	  	<td width="191">Localidad:</td>
			  </tr>
              <tr>
                	<td colspan="2"><?php generaSelect("un","estado"); ?></td>
                	<td>&nbsp;</td>
                	<td colspan="2"><?php generaSelect("un","municipio"); ?></td>
                	<td>&nbsp;</td>
                	<td><?php generaSelect("una" ,"localidad"); ?></td>
              	</tr>
                <tr>
                	<td colspan="7"><div id="otro"  class="element"><?php generaCampos("c_pubplace","text","Ingrese el lugar de publicación"); ?> </div></td>
               	</tr>
              	<tr>
                	<td>Fecha de publicación:</td>
                	<td colspan="3"><?php generaCampos("c_pubdate","fecha","Fecha de elaboración o modificación de dato geoespacial"); ?></td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Versión:</td>
                	<td colspan="3"><?php generaCampos("c_edition","text","Sinónimo de edición" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Escala:</td>
                	<td colspan="3"><?php generaCampos("c_escala","text","Escala gráfica del dato geoespacial" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Clave:</td>
                	<td colspan="3"><?php generaCampos("c_clave","text","Clave de proyecto asignada por CONABIO" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Nivel de avance:</td>
                	<td colspan="3"><?php generaCampos("c_avance","text","" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Mantenimiento:</td>
                	<td colspan="3"><?php generaCampos("c_mantenimiento","text","Frecuencia de actualización del dato" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              	</tr>
              	<tr>
                	<td>Descripción del metadato:</td>
                	<td colspan="7"><?php generaCampos("c_issue","textarea","Información complementaria a la cita del dato geoespacial" ); ?></td>
                	</tr>
              	<tr>
                	<td>Resumen:</td>
                	<td colspan="7"><?php generaCampos("c_resumen","textarea","Descripción breve del contenido, área cubierta y tema que representa el dato" ); ?></td>
                	</tr>
              	<tr>
                	<td>Abstract:</td>
                	<td colspan="7"><?php generaCampos("c_abstract","textarea","" ); ?></td>
                </tr>
              	<tr>
                	<td>Objetivos generales:</td>
                	<td colspan="7"><?php generaCampos("c_objetivo","textarea","Propósito de la creación del dato" ); ?></td>
                </tr>
              	<tr>
                	<td>Datos complementarios:</td>
                	<td colspan="7"><?php generaCampos("c_datos_comp","textarea","Información complementaria a cerca del dato" ); ?></td>
                </tr>
              	<tr>
                	<td>Formato del dato geoespacial:</td>
                	<td colspan="7"><?php generaCampos("c_geoform","text","Formato digital correspondiente a los lineamientos cartográficos estipulados por CONABIO" ); ?></td>
                </tr>
              	<tr>
                	<td>Tamaño del dato geoespacial:</td>
                	<td colspan="3"><?php generaCampos("c_tamano","text","Tamaño en megabytes del o los archivos que contiene el dato" ); ?></td>
                	<td colspan="2">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
              </tr>
              <tr>
              		<td>Tiempo comprendido:</td>
                	<td width="29">del:</td>
                	<td width="153"><?php generaCampos("c_tiempo","fecha","" ); ?></td>
                	<td>al:</td>
                	<td width="153"><?php generaCampos("c_tiempo2","fecha","" ); ?></td>
                	<td width="35">&nbsp;</td>
                	<td>&nbsp;</td>
                	<td>&nbsp;</td>
            </tr>
            <tr>
               	 <td>Ligas www:</td>
                	<td colspan="7"><div id="ligas"><?php tabla('divLigas' , 'Ligas WWW:', 'l_liga_www','ligas', 0 , 0 , 0 ); ?></div></td>
            </tr>
        </table>
    	</form>
    </div>
         
    <div id="div2"   class="element">
        <form name="restricciones" method="post" action='php/guardar.php?hoja=restricciones&cv_principal=<?php echo $cv_principal;?>' id="restricciones">
        	<input type="button" value = "Guardar"  id="form2" />
            <input type="hidden" name="id_metadato"   id="id_metadato2" />
            <input type="hidden" name="displayBlock"   value="2" />
            <table width="870" border="0">
            	<tr>
                	<td colspan="2"><h3>Restricciones</h3></td>
                </tr>
                <tr>
                  	<td width="195">Acceso:</td>
                  	<td><?php generaCampos("c_acceso","text","Restricciones y prerrequisitos legales del acceso al dato"); ?></td>
                </tr>
                <tr>
                  	<td>Uso:</td>
                  	<td><?php generaCampos("c_uso","text","Restricciones y prerrequisitos legales del uso del dato" ); ?></td>
                </tr>
                <tr>
                  	<td>Observaciones:</td>
                  	<td><?php generaCampos("c_observaciones","textarea",""); ?></td>
                </tr>
           	</table>
        </form>
    </div>
    
    <div  id="div3"  class="element">
    	 <form name="palabrasClave"  method="post" action='php/guardar.php?hoja=palabrasClave&cv_principal=<?php echo $cv_principal;?>' id="palabrasClave">
        	<input type="button" value = "Guardar"  id="form3" />
             <input type="hidden" name="id_metadato"   id="id_metadato3" />
             <input type="hidden" name="displayBlock"   value="3" />
            <table width="870" border="0">
                <tr>
                    <td width="195"><h3>Temas: <?php toolTip("Palabras o frases, en forma de lista, que indican el significado o idea principal del tema del dato"); ?></h3> </td>
                    <td colspan="7"><?php tabla('divTemas' , 'Temas	', 'm_Palabra_Clave','Temas', 0 , 0 , 0 ); ?></td>
                 </tr>
                 <tr>
                     <td><h3>Sitios: <?php toolTip("Nombres de lugares, municipios, entidades federativas, rasgos geográficos, regiones, etc., que aluden a la distribución geográfica del dato"); ?></h3></td>
                     <td colspan="7"><?php tabla('divSitios' , 'Sitios	', 's_Sitios_Clave','Sitios', 0 , 0 , 0 ); ?></td>
                  </tr>
              </table>
     	</form>
     </div>
     
     <div id="div4"  class="element">
     	<form name="ambiente" method="post" action='php/guardar.php?hoja=ambienteDeTrabajo&cv_principal=<?php echo $cv_principal;?>' id="ambiente">
        	<input type="button" value = "Guardar"  id="form4" />
            <input type="hidden" name="id_metadato"   id="id_metadato4" />
            <input type="hidden" name="displayBlock"   value="4" />
            <table width="870" border="1">
                <tr>
                  	<td>Software y hardware:</td>
                  	<td><?php generaCampos("c_software_hardware","text","Programa de cómputo utilizado, incluyendo versión y equipo para elaboración del dato geoespacial"); ?></td>
                </tr>
                <tr>
                  	<td width="195">Sistema operativo:</td>
                  	<td><?php generaCampos("c_sistema_operativo","text","Nombre y versión del sistema operativo instalado en el equipo de cómputo empleado"); ?></td>
                </tr>
                <tr>
                  	<td>Requisitos técnicos:</td>
                  	<td><?php generaCampos("c_tecnicos","text","Especificaciones de software y hardware requerido para utilizar el dato, si es necesario"); ?></td>
                </tr>
                <tr>
                  	<td>Ruta y nombre de archivo:</td>
                  	<td><?php generaCampos("c_path","text",""); ?></td>
                </tr>
           </table>
        </form>
     </div> 
     <div   id="div5"  >
         <form name="calidad" method="post" action='php/guardar.php?hoja=calidadDeDatos&cv_principal=<?php echo $cv_principal;?>' id="calidad">
         	<input type="button" value = "Guardar"  id="form5" />
             <input type="hidden" name="id_metadato"   id="id_metadato5" />
             <input type="hidden" name="displayBlock"   value="5" />
            <table width="870" border="1">
        		<tr>
                  	<td width="210">Metodología:</td>
          			<td width="644"><?php generaCampos("c_metodologia","text","Tipo de investigación según el lugar de aplicación para obtener o generar los datos"); ?></td>
              	</tr>
                <tr>
                  	<td width="210">Descripción de la metodología:</td>
                  	<td><?php generaCampos("c_descrip_metodologia","textarea","Se describe, de manera general, el o los métodos empleados en el proceso de elaboración del dato "); ?></td>
                </tr>
                <tr>
                  	<td>Descripción del proceso:</td>
                  	<td><?php generaCampos("c_descrip_proceso","textarea","Describe ampliamente cómo se hizo el dato,explicando lo realizado en cada uno de los métodos empleados"); ?></td>
                </tr>
                <tr>
                  	<td colspan="2"><h3>Referencia de los datos originales</h3></td>
                </tr>
          </table>
           <div id="datosOrigin" ><?php tabla_d('divDatosOrig' , 'Datos','Datos', 0 , 0 , 0 ); ?></div>
        </form>
     </div>
     <div  id="div6"  class="element">
     	<form name="taxon" method="post" action='php/guardar.php?hoja=taxonomia&cv_principal=<?php echo $cv_principal;?>' id="taxon">
         	<input type="button" value = "Guardar"  id="form6" />
             <input type="hidden" name="id_metadato"   id="id_metadato6" />
             <input type="hidden" name="displayBlock"   value="6" />
            <table width="869">
            	<tr >
                	<td width="230">&nbsp;</td>
                    <td width="627">&nbsp;</td>
                </tr>
                <tr>
                     <td colspan="2"><h3>Taxonom&iacute;a:</h3></td>
                </tr>
                <tr>
                     <td width="230"></td>
                     <td width="627">&nbsp;</td>
                 </tr>
            </table>
            <div class="row">
            	<div class="col-lg-12">
            		<div id="bibliography-div" class="container">
            			
            		</div>
            	</div>
            </div>
            <div id="datosTaxon"><?php tabla_t('divDatosTaxon' , 'taxonomía', "taxones", 0 , 0 , 0); ?></div>
        </form>
     </div>
     <div  id="div15"  class="element">

     	<br>
     	<br>
     	
     	<h3>Buscar Taxonomía</h3>
     	
     	<div class="container">

     		<br>
     		
     		<div class="row">
     			<div class="col-lg-3">
	     			Palabra clave:
	     		</div>
	     		<div class="col-lg-7">
	     			<input type="text" id="palabra-clave" class="form-control">
	     			<?php toolTip("Instroduzca un nombre o parte del nombre de la especie que quiere encontrar"); ?> 
	     		</div>
	     		<div class="col-lg-2">
	     			<button type="button" id="buscar-taxonomia">Buscar</button>
	     		</div>

     		</div>

     		<br>
     		<br>

     		<div class="row" style="height: 400px;">
     			<div class="col-lg-12" style="height: 400px; overflow:scroll;">
     				<table id="toxonomy-results" class="table" style="height: 400px;">
     					<thead>
     						<tr>
     							<th>
     								Taxonomía
     							</th>
     							<th>
     								Bibliografía
     							</th>
     							<th>
     								Agregar
     							</th>
     						</tr>
     					</thead>
     					<tbody>
     						
     					</tbody>
     				</table>
     			</div>
     		</div>
     	</div>


     </div>
     <div  id="div7"  class="element">
        <form name="espacial" method="post" action='php/guardar.php?hoja=proyeccion&cv_principal=<?php echo $cv_principal;?>' id="espacial" class="formulario">
          		<input type="button" value = "Guardar"  id="form7" />
                 <input type="hidden" name="id_metadato"   id="id_metadato7" />
                 <input type="hidden" name="displayBlock"   value="7" />
                <table width="870" border="1">
                    <tr>
                      <td colspan="2"><input name="userfile" type="file" class="box" id="userfile" /></td>
                      <td>shp: <select name="selectVector" id="selectVector">
                          <option value="0">Seleccione un archivo</option>
                        </select></td>
                      <td>raster: <select name="selectTif" id="selectTif">
                          <option value="0">Seleccione un archivo</option>
                        </select></td>
                    </tr>
                    <tr>
                       <td colspan="4"><h3>Información Espacial:</h3></td>
                    </tr>
                    <tr>
                      <td>Área Geográfica:</td>
                      <td width="624" colspan="3"><?php generaCampos("c_area_geo","textarea","Descripción textual breve de la distribución geográfica del dato"); ?></td>
                    </tr>
                    <tr>
                      <td width="230">Estructura del dato:</td>
                      <td colspan="3"><?php generaCampos("c_estructura_dato","text","Se especifica la estructura del dato geoespacial (Vector o Raster)."); ?></td>
                    </tr>
                    <tr>
                      <td>Tipo del dato:</td>
                      <td colspan="3"><?php generaCampos("c_tipo_dato","text","Representado por: puntos, líneas y polígonos (si la estructura es vectorial); y píxel (si la estructura es raster)."); ?></td>
                    </tr>
                    <tr>
                      <td>Numero total del dato:</td>
                      <td colspan="3"><?php generaCampos("c_total_datos","text","Número total de elementos que contiene el dato geoespacial"); ?></td>
                    </tr>
                    <tr>
                      <td colspan="4"><h3>Coordenadas del Extremo:</h3></td>
                    </tr>
                    <tr>
                      <td>Coordenadas del extremo oeste:</td>
                      <td colspan="3"><?php generaCampos("c_oeste","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Coordenadas del extremo este:</td>
                      <td colspan="3"><?php generaCampos("c_este","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Coordenadas del extremo norte:</td>
                      <td colspan="3"><?php generaCampos("c_norte","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Coordenadas del extremo sur:</td>
                      <td colspan="3"><?php generaCampos("c_sur","text",""); ?></td>
                    </tr>
                    <tr>
                      <td colspan="4"><h3>Proyección Cartográfica:</h3></td>
                    </tr>
                    <tr>
                      <td>Proyección:</td>
                      <td colspan="3"><?php generaCampos("c_id_proyeccion","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Datum horizontal:</td>
                      <td colspan="3"><?php generaCampos("c_datum","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Nombre del elipsoide:</td>
                      <td colspan="3"><?php generaCampos("c_elipsoide","text",""); ?></td>
                    </tr>
                    <tr>
                      <td colspan="4"><h3>Si la estructura es Raster :</h3></td>
                    </tr>
                    <tr>
                      <td>Numero de renglones:</td>
                      <td colspan="3"><?php generaCampos("r_nun_renglones","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Numero de columnas:</td>
                      <td colspan="3"><?php generaCampos("r_num_columnas","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Tamaño del pixel de X en metros:</td>
                      <td colspan="3"><?php generaCampos("r_pixel_X","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Tamaño del pixel de Y en metros:</td>
                      <td colspan="3"><?php generaCampos("r_pixel_Y","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Coordenada X del origen del raster:</td>
                      <td colspan="3"><?php generaCampos("r_COOR_X","text",""); ?></td>
                    </tr>
                    <tr>
                      <td>Coordenada Y del origen del raster:</td>
                      <td colspan="3"><?php generaCampos("r_COOR_Y","text",""); ?></td>
                    </tr>
              </table>
         	</form>
     </div>
     <div id="div8"  class="element">
        <form name="atributos" method="post" action='php/guardar.php?hoja=atributos&cv_principal=<?php echo $cv_principal;?>' id="atributos">
     		<input type="button" value = "Guardar"  id="form8" />
            <input type="hidden" name="id_metadato"   id="id_metadato8" />
            <input type="hidden" name="displayBlock"   value="8" />
            <table width="869" border="0">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                     <td width="241">Nombre de la Entidad (Tabla):</td>
                     <td width="618"><?php  generaCampos("c_tabla","text",""); ?> </td>
                </tr>
                <tr>
                     <td>Descripci&oacute;n de la Entidad (Tabla):</td>
                     <td><?php  generaCampos("c_descrip_tabla","textarea","" );?></td>
                </tr>
                <tr>
                      <td colspan="2"><h3>Atributos</h3></td>
                </tr>
             </table>
            <table width="1249" border="1">
                 <tr>
                      <td width="350" align="center">Nombre</td>
                      <td width="395" align="center">Descripci&oacute;n</td>
                      <td width="200" align="center">Fuente</td>
                      <td width="150" align="center">Unidades de medida</td>
                      <td width="120" align="center">Tipo de dato</td>
                 </tr>
            </table>
             
           	<div id="formAtributos"><?php tabla_a("divAtributos",0 , 0 , 0  );?></div>
         </form>
     </div>
     <div id="div9"   class="element">
     	<form name="datos" method="post" action='php/guardar.php?hoja=arbol&cv_principal=<?php echo $cv_principal;?>' id="clasificacion">
        	<input type="button" value = "Guardar"  id="form9" />
            <input type="hidden" name="id_metadato"   id="id_metadato9" />
            <input type="hidden" name="displayBlock"   value="9" />
				<table width="869" border="0">
                	<tr>
                    	<td>&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                   	<tr>
                       <td width="118"><h3>Clasificaci&oacute;n</h3></td>
                       <td width="657"><input type="text" name="c_clasif_ruta" class="extenso" /></td>
                       <td width="80"><?php  generaCampos("c_clasificacion","text",""); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="3">&nbsp;</td>
                     </tr>
                 </table>
				<div class="divClasificacion"><?php  crea_arbol("divArbol" , 0 ,0); ?> </div>      
      </form>
     </div>

     <div id="div10" class="element">
        <input type="button" value="Descargar ejecutable" id="descarga"/>
     </div>

     <div id="div11" class="element">

     	<br>
     	<br>
     	
     	<h3>Registrar Capturista</h3>

     	<form action="registrar_capturista.php" method="POST">
     		<div class="row">
			    <div class="col-lg-10 offset-lg-1">
		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Nombre:</label><br>
			     			<input type="text" name="nombre" required="true" class="form-control"><?php toolTip("Nombre de pila del capturista"); ?>
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Apellido(s):</label><br>
					     	<input type="text" name="apellido"required="true" class="form-control"><?php toolTip("Apellido(s) del capturista"); ?> 
		     			</div>
		     		</div>

		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Puesto:</label><br>
			     			<input type="text" name="puesto" required="true" class="form-control"><?php toolTip("Cargo que desempeña"); ?> 
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Teléfono:</label><br>
					     	<input type="text" name="telefono" required="true" class="form-control"><?php toolTip("Teléfono del capturista"); ?> 
		     			</div>
		     		</div>

			     	<div class="row">
			     		<div class="col-lg-6">
			     			<label>Email:</label><br>
					     	<input type="email" name="email" class="form-control" required="true"><?php toolTip("Correo electrónico del capturista"); ?> 
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-6">
				     		<label>Contraseña:</label><br>
				     		<input type="password" name="pass" class="form-control" required="true"><?php toolTip("Contraseña"); ?> 
				     	</div>
				     	<div class="col-lg-6">
			     			<label>Confimación de contraseña:</label><br>
			     			<input type="password" name="pass1" class="form-control" required="true"><?php toolTip("Repetir contraseña"); ?>
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12">
			     			<input type="hidden" name="token" value="<?php echo $token;?>">
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12" style="text-align: right;">
			     			<input type="submit" name="submit" value="Registrar">
			     		</div>
			     	</div>

			     	<br>
			     	<br>
				</div>
			</div>
     	</form>

     </div>

     <div id="div12" class="element">

     	<br>
     	<br>
     	
     	<h3>Registrar Analista</h3>

		<form action="registrar_analista.php" method="POST">
     		<div class="row">
			    <div class="col-lg-10 offset-lg-1">
		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Nombre:</label><br>
			     			<input type="text" name="nombre" required="true" class="form-control">
			     			<?php toolTip("Nombre de pila del analista"); ?> 
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Apellido:</label><br>
					     	<input type="text" name="apellido"required="true" class="form-control"><?php toolTip("Apellido del analista"); ?> 
		     			</div>
		     		</div>

		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Puesto:</label><br>
			     			<input type="text" name="puesto" required="true" class="form-control">
			     			<?php toolTip("Cargo que desempeña"); ?> 
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Teléfono:</label><br>
					     	<input type="text" name="telefono" required="true" class="form-control"><?php toolTip("Teléfono del analista"); ?> 
		     			</div>
		     		</div>

			     	<div class="row">
			     		<div class="col-lg-6">
			     			<label>Email:</label><br>
					     	<input type="email" name="email" class="form-control" required="true">
					     	<?php toolTip("Correo electrónico del analista"); ?> 
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-6">
				     		<label>Contraseña:</label><br>
				     		<input type="password" name="pass" class="form-control" required="true"><?php toolTip("Contraseña"); ?> 
				     	</div>
				     	<div class="col-lg-6">
			     			<label>Confimación de contraseña:</label><br>
			     			<input type="password" name="pass1" class="form-control" required="true"><?php toolTip("Repetir contraseña"); ?> 
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12">
			     			<input type="hidden" name="token" value="<?php echo $token;?>">
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12" style="text-align: right;">
			     			<input type="submit" name="submit" value="Registrar">
			     		</div>
			     	</div>

			     	<br>
			     	<br>
				</div>
			</div>
     	</form>     	

     </div>

     <div id="div13" class="element">

     	<br>
     	<br>
     	
     	<h3>Registrar Administrador</h3>

		<form action="registrar_administrador.php" method="POST">
     		<div class="row">
			    <div class="col-lg-10 offset-lg-1">
		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Nombre:</label><br>
			     			<input type="text" name="nombre" required="true" class="form-control">
			     			<?php toolTip("Nombre de pila del administrador"); ?> 
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Apellido:</label><br>
					     	<input type="text" name="apellido"required="true" class="form-control"><?php toolTip("Apellido(s) del administrador"); ?> 
		     			</div>
		     		</div>

		     		<div class="row">
		     			<div class="col-lg-6">
		     				<label>Puesto:</label><br>
			     			<input type="text" name="puesto" required="true" class="form-control">
			     			<?php toolTip("Cargo que desempeña"); ?> 
		     			</div>
		     			<div class="col-lg-6">
		     				<label>Teléfono:</label><br>
					     	<input type="text" name="telefono" required="true" class="form-control"><?php toolTip("Teléfono del administrador"); ?> 
		     			</div>
		     		</div>

			     	<div class="row">
			     		<div class="col-lg-6">
			     			<label>Email:</label><br>
					     	<input type="email" name="email" class="form-control" required="true">
					     	<?php toolTip("Correo electrónico del administrador"); ?> 
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-6">
				     		<label>Contraseña:</label><br>
				     		<input type="password" name="pass" class="form-control" required="true"><?php toolTip("Contraseña"); ?> 
				     	</div>
				     	<div class="col-lg-6">
			     			<label>Confimación de contraseña:</label><br>
			     			<input type="password" name="pass1" class="form-control" required="true"><?php toolTip("Repetir contraseña"); ?> 
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12">
			     			<input type="hidden" name="token" value="<?php echo $token;?>">
			     		</div>
			     	</div>

			     	<div class="row">
			     		<div class="col-lg-12" style="text-align: right;">
			     			<input type="submit" name="submit" value="Registrar">
			     		</div>
			     	</div>

			     	<br>
			     	<br>
				</div>
			</div>
     	</form>     	

     </div>

     <div id="div14" class="element">

     	<br>
     	<br>
     	
     	<h3>Borrar Usuario</h3>

     	<form action="" method="POST">

     		<div class="row">
			    <div class="col-lg-10 offset-lg-1">
		     		<div class="row">
		     			<div class="col-lg-12">
		     				<label>Usuarios disponibles:</label><br>
					     	<select name="user" required="true" class="form-control" id="borrar-usuario-select">
					     		<option value="">Seleccione un usuario</option>
					     		<?php

					     			foreach ($users as $u) {
					     				echo '<option value="'.$u['user_id'].'">'.$u['name'].'</option>';
					     			}

					     		?>
					     	</select>
					     	<?php toolTip("Seleccione el usuario que desea borrar"); ?> 
		     			</div>
		     		</div>

		     	</div>
		     </div>

		     <br>

     		 <div class="row">
	     		<div class="col-lg-1 offset-lg-8">
	     			<input type="button" id="borrar-usuario-button" value="Borrar" disabled="true">
	     		</div>
	     	</div>

	     	<br>
	     	<br>
	     	<br>

     	</form>

     </div>
             
	 <div id="dialog_nuevo"  title="Crear nuevo metadato">
        <p class="validateNuevo">Inserte el nombre del metadato</p>
        <form name="nuevo" method="post" action="nuevo.php?cv_principal=<?php echo $cv_principal;?>" id="formNuevo">
            <input type="text" name="name" id="name" class="text  ui-corner-all" />
            <input type="hidden" name="nuevo_metadato"   id="nuevoM" />
        </form>
     </div> 

     <div id="reabrir-modal-container"  title="Reabrir metadato">
        <p>¿Deseas reabrir metadato?</p>
        <form method="post" action="php/reabrir.php" id="reabrir-metadato-form">
            <input type="hidden" name="metadato"   id="reabrir-metadato-hidden" />
        </form>
     </div>

     <div id="cerrar-modal-container"  title="Cerrar metadato">
        <p>¿Deseas cerrar metadato?</p>
        <form method="post" action="php/cerrar.php" id="cerrar-metadato-form">
            <input type="hidden" name="metadato"   id="cerrar-metadato-hidden" />
        </form>
     </div> 

     <div id="borrar-usuario-modal-container"  title="Borrar usuario">
        <p>¿Deseas borrar el usuario seleccionado?</p>
        <form method="post" action="borrar_usuario.php" id="borrar-usuario-form">
            <input type="hidden" name="user"   id="borrar-usuario-hidden" />
        </form>
     </div> 
     
    <div id="eliminar_metadato" title="Eliminar metadato"  >
		<form name="terminoSesion" method="post" action="php/borrar.php?cv_principal=<?php echo $cv_principal;?>" id="formDeleteM">
        	<input type="hidden" name="delete_metadato"   id="deleteM" />
			<img src="img/alert.jpg" class="alert"  />
            <p class="validateDelete">El metadato de borrar&aacute; completamente de la base de datos.</p>
        </form>
	</div>
    <div id="cerrar_sesion" title="Esta a punto de salir del sistema"  >
		<form name="terminoSesion" method="post" action="php/cerrarSesion.php" id="formTerminoSesion">
			<img src="img/alert.jpg" class="alert"  /><p class="validateTips">Desea finalizar la sesi&oacute;n</p>
        </form>
	</div>
    
   
 </div> <!--FIN <div id="rg">-->
        
 	<script src="https://cdn.auth0.com/js/auth0-spa-js/1.2/auth0-spa-js.production.js"></script>
  	<script src="public/js/app.js"></script>

</body>
</html>
<?php
}
?>