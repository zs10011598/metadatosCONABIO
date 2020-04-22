<?php
ini_set("display_errors", "on");
header('Content-Type: text/html; charset=utf-8'); 
require('conexion.php');
require_once('sentencias.php');
require_once('genera.php');
$db = conectar();
$cat = catalogos();

$jsondata = array();

if($db)
{
	$caso= $_GET['var3'];
	switch ($caso)
	{
		case "index":
		{
			if( !empty($_GET['var1']) and !empty($_GET['var2']) )
			{
				$usuario = $_GET['var1'];
				$paswword = $_GET['var2'];
				$consulta = pg_query($db, "SELECT * FROM analistas where nom_user='$usuario' AND password = '$paswword'");
				if(pg_num_rows ($consulta))
				{    
					if( $fila=pg_fetch_array($consulta) )
					{
						session_start();
						$autentificado = 'SI';
						$uid = $fila['idAnalista'];
						$_SESSION['uid']= $uid;
						$_SESSION['autenticado']    = $autentificado;
						$jsondata["success"] = true ;
					} 
				}
				else
				{
					$jsondata["success"] = false ;
					$jsondata['message'] = 'Verificar usuario y contraseña';
				}
			}
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
		}break;
		
		case "list":
		{
			$jsondata["success"] = true;
				
			$cv_principal  = $_GET['var1'];
			$admin = $_GET['var4'];
			$consulta = sql("seleccion",0 ,$cv_principal);
			
			if ($admin == 1){$result = pg_query($db, $consulta [0]);}
			if ($admin <> 1){$result = pg_query($db, $consulta [1]);}
								
			$jsondata["numRows"] = pg_num_rows($result);
			$jsondata["listId"] = array();
			while ($fila = pg_fetch_array($result, null, PGSQL_ASSOC))
			{
				$jsondata["listId"] [] = $fila ;
			}

			
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;

		case "values":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			$jsondata["success"] = true;			
			$campos = sql("campos",$id ,$cv_principal);
			$resCampos = pg_query($db, $campos [0]);
			$jsondata["listCampo"] = array();
			while ($fila = pg_fetch_array($resCampos, null, PGSQL_ASSOC))
			{
				$jsondata["coberturas"] [] = $fila ;
			}
					
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;
		
		case "raster":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			$jsondata["success"] = true;			
			$campos = sql("raster",$id ,$cv_principal);
			$resCampos = pg_query($db, $campos [0]);
			while ($fila = pg_fetch_array($resCampos, null, PGSQL_ASSOC))
			{
				$jsondata["raster"] [] = $fila ;
			}
					
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;
		
		case "divAutores":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			tabla("divAutoresId" ,"Autores:" , "x_origin",  'autores' , $cv_principal , $id , $db);
				
		}break;

		case "guardaAutores":
		{
			$value  = $_GET['origin'];
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var2'];
			guardaTablas("divAutoresId" ,"Autores:" , "x_origin",  'autores' , $cv_principal , $id , $db, $value);
				
		}break;

		case "deleteAutores":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var2'];
			delete($id , $cv_principal , "x_origin" );						
				
		}break;

		case "ligas":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			tabla("divLigas" ,"Ligas WWW:" , "l_liga_www", 'ligas' , $cv_principal , $id , $db);
				
		}break;

		case "guardaLigas":
		{
			$value  = $_GET['origin'];
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var2'];
			guardaTablas("divLigas" ,"Ligas WWW:" , "l_liga_www", 'ligas' , $cv_principal , $id , $db, $value);
				
		}break;

		case "deleteLigas":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var2'];
			delete($id , $cv_principal , "l_liga_www" );						
				
		}break;
		
		case "estado":
		{
			$cv_principal  = $_GET['cv_principal'];
		
			$jsondata["listId"] = array();
			$jsondata["id_edo"] = array();
			$jsondata["edo"] = "";				
			
			
			$consulta = sql("estado",0 ,0);
			$result = pg_query($cat, $consulta [0]);
			while ($fila = pg_fetch_array($result, null, PGSQL_ASSOC))
			{	$jsondata["listId"] [] = $fila ;	}
			
			if($cv_principal != 0){	
				$jsondata["edo"] = $cv_principal;
				$sql_edo = sql("estado",$cv_principal ,0);
				$res_edo = pg_query($db, $sql_edo [1]);
				while ($fila = pg_fetch_array($res_edo))
				{	$jsondata["id_edo"] = $fila [0] ;		}
			}
			
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;
		
		case "municipio":
		{
			$jsondata["listId"] = array();
			$jsondata["idEdo"] = array();
			$jsondata["idMuni"] = array();
				
			$id_edo  = $_GET['id_edo'];
			$cv_principal = $_GET['cv_principal'];
			
			if($id_edo != 0 ){	
				$sql_munis = sql("municipio",$id_edo ,0);
				$res_munis = pg_query($cat, $sql_munis [0]);
					
				while ($fila = pg_fetch_array($res_munis, null, PGSQL_ASSOC))
				{	$jsondata["listId"] [] = $fila ;	}
			}
			
			if($cv_principal != 0){	
			
				$sql_edo = sql("estado",$cv_principal ,0);
				$res_edo = pg_query($db, $sql_edo [1]);
				while ($fila = pg_fetch_array($res_edo))
				{	
					$id_edo = $fila [0];		
					$id_mun = $fila [1];
				}
				
				$jsondata["idMuni"] = $id_mun;
				
				$sql_munis = sql("municipio",$id_edo ,0);
				$res_munis = pg_query($cat, $sql_munis [0]);
				while ($fila = pg_fetch_array($res_munis, null, PGSQL_ASSOC))
				{	$jsondata["listId"] [] = $fila ;	}
			}
			
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;
		
		case "localidad":
		{
			$jsondata["listId"] = array();
			$jsondata["idLoc"] = array();
			$jsondata["loc"] = "";
				
			$id_mun  = $_GET['id_mun'];
			$cv_principal = $_GET['cv_principal'];

			$consulta = sql("localidad",$id_mun ,0);
			$result = pg_query($cat, $consulta [0]);
			
			if($id_mun != 0){			
				$jsondata["idMun"] = $id_mun;
				$sql_locs = sql("localidad",$id_mun ,0);
				$res_locs = pg_query($cat, $sql_locs [0]);
				
				while ($fila = pg_fetch_array($res_locs, null, PGSQL_ASSOC))
				{	$jsondata["listId"] [] = $fila ;		}
			}
			
			if($cv_principal != 0){	
			
				$sql_edo = sql("estado",$cv_principal ,0);
				$res_edo = pg_query($db, $sql_edo [1]);
				while ($fila = pg_fetch_array($res_edo))
				{			
					$id_mun = $fila [1];
					$id_loc = $fila [2];
				}
				
				$jsondata["idLoc"] = $id_loc;
				
				$sql_munis = sql("localidad",$id_mun ,0);
				$res_munis = pg_query($cat, $sql_munis [0]);
				while ($fila = pg_fetch_array($res_munis, null, PGSQL_ASSOC))
				{	$jsondata["listId"] [] = $fila ;	}
			}
								
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;
		
		case "divTemas":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			tabla('divTemas' , 'Temas', 'm_Palabra_Clave','Temas' , $cv_principal , $id , $db);
				
		}break;
		
		case "divSitios":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			tabla('divSitios' , 'Sitios', 's_Sitios_Clave','Sitios', $cv_principal , $id , $db);
				
		}break;
		
		case "guardaDatos":
		{
			
			$cv  = $_GET['var1'];
			$id  = $_GET['var2'];

			$jsondata["success"] = "guardaDatos";	
			$jsondata["clave"] = $cv;
			$jsondata["id"] = $id;
			
			$sqlupd  = "UPDATE coberturas SET ";  
			$sqlupd .= "nombre 			= '".$_GET['c_nombre']			."',";
			$sqlupd .= "cobertura 		= '".$_GET['c_cobertura']		."',";
			$sqlupd .= "fecha_inicial 	= '".$_GET['c_fecha_inicial']	."',";
			$sqlupd .= "fecha 			= '".$_GET['c_fecha'] 			."',";
			$sqlupd .= "version 		= '".$_GET['c_version'] 		."',";
			$sqlupd .= "publish 		= '".$_GET['c_publish'] 		."',";
			$sqlupd .= "publish_siglas 	= '".$_GET['c_publish_siglas']	."',";
			$sqlupd .= "pubplace_edo 	= '".$_GET['estado'] 			."',";
			$sqlupd .= "pubplace_muni 	= '".$_GET['municipio'] 		."',";
			$sqlupd .= "pubplace_loc 	= '".$_GET['localidad']			."',";
			$sqlupd .= "pubplace 		= '".$_GET['pubplace']			."',";

			$sqlupd .= "pubdate 		= '".$_GET['c_pubdate'] 		."',";
			$sqlupd .= "edition 		= '".$_GET['c_edition'] 		."',";
			$sqlupd .= "escala 			= '".$_GET['c_escala'] 			."',";
			$sqlupd .= "clave 			= '".$_GET['c_clave'] 			."',";
			$sqlupd .= "avance			= '".$_GET['c_avance'] 			."',";
			$sqlupd .= "mantenimiento 	= '".$_GET['c_mantenimiento'] 	."',";
			$sqlupd .= "issue 			= '".$_GET['c_issue'] 			."',";
			$sqlupd .= "resumen 		= '".$_GET['c_resumen'] 		."',";
			$sqlupd .= "abstract 		= '".$_GET['c_abstract'] 		."',";
			$sqlupd .= "objetivo 		= '".$_GET['c_objetivo'] 		."',";
			$sqlupd .= "datos_comp 		= '".$_GET['c_datos_comp'] 		."',";
			$sqlupd .= "geoform 		= '".$_GET['c_geoform'] 		."',";
			$sqlupd .= "tamano 			= '".$_GET['c_tamano'] 			."',";
			$sqlupd .= "tiempo 			= '".$_GET['c_tiempo'] 			."',";
			$sqlupd .= "tiempo2			= '".$_GET['c_tiempo2'] 		."'";
			$sqlupd .= "WHERE record_id 	= '" .$id 		."'";
				
			$sql_div1 =  pg_query($db, $sqlupd);	
			
			if (!$sql_div1) { $jsondata["message"] = "Error al guardar datos generales"; } 
			else  			{ $jsondata["message"] = "El metadato a sido guardado"; } 
				
					
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;

		case "guardaRestri":
		{
			
			$cv  = $_GET['var1'];
			$id  = $_GET['var2'];

			$jsondata["clave"] = $cv;
			$jsondata["id"] = $id;
			
			$sqlupd  = "UPDATE coberturas SET ";  
			$sqlupd .= "acceso 			= '".$_GET['c_acceso']			."',";
			$sqlupd .= "uso 			= '".$_GET['c_uso']				."',";
			$sqlupd .= "observaciones 	= '".$_GET['c_observaciones']	."'";
			$sqlupd .= "WHERE record_id = '" .$id 		."'";
				
			$sql_div1 =  pg_query($db, $sqlupd);	
			
			if (!$sql_div1) { $jsondata["message"] = "Error al guardar restricciones"; } 
			else  			{ $jsondata["message"] = "El metadato a sido guardado"; } 
				
					
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
				
		}break;

		case "datosOrigin":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			
			tabla_d('divDatosOrigID' , 'Datos', 'Datos', $cv_principal , $id , $db );
				
		}break;
		
		case "datosTaxon":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			
			tabla_t('divDatosTaxon' , 'taxonomía', "taxones", $cv_principal ,$id  ,$db );
				
		}break;
		
		case "formAtributos":
		{
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			
			tabla_a("divAtributos", $cv_principal ,$id  ,$db );
				
		}break;
		
		case "divClasificacion":
		{
		
			$db = conectar();	
			$cv_principal  = $_GET['var1'];
			$id  = $_GET['var4'];
			
			$jsondata["success"] = true;
			
			$sql_arbol = sql("carga_arbol",$id ,$cv_principal);
			$res_arbol = pg_query($db, $sql_arbol [0]);
								
			$jsondata["numRows"] = pg_num_rows($res_arbol);
			$jsondata["listClas"] = array();
			while ($fila = pg_fetch_array($res_arbol, null, PGSQL_ASSOC))
			{
				$jsondata["listClas"] [] = $fila ;
			}

			
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($jsondata);
			exit();
			
		}break;

		default:
	}
	
	
	
}



		
							

	
		
?>