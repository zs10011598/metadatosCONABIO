<?php 
ini_set("display_errors", "on");
header('Content-Type: text/html; charset=utf-8');
require('conexion.php');
$db = conectar();
 
$contenido = $_GET["hoja"];
$record_id = $_POST["id_metadato"];
$cv_principal = $_GET["cv_principal"];
$displayBlock = $_POST["displayBlock"];
$valor_null = "";
$null_numeric = 0;
		
switch ($contenido)
{
	case "datos":
	{
		//<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 1 >>>>>>>>>>>>>
		$sqlupd  = "UPDATE coberturas SET ";  
		$sqlupd .= "nombre 			= '".$_POST['c_nombre'] 		."',";
		$sqlupd .= "cobertura 		= '".$_POST['c_cobertura'] 		."',";
		$sqlupd .= "publish 		= '".$_POST['c_publish'] 		."',";
		$sqlupd .= "publish_siglas 	= '".$_POST['c_publish_siglas'] ."',";
		$sqlupd .= "pubplace_edo	= '".$_POST['estado'] 			."',";
			
		if (isset($_POST['municipio']))  { $sqlupd .= "pubplace_muni	= '".$_POST['municipio']."',";}
		if (isset($_POST['localidad']))  { $sqlupd .= "pubplace_loc		= '".$_POST['localidad']."',";}
		if (isset($_POST['c_pubplace'])) { $sqlupd .= "pubplace			= '".$_POST['c_pubplace']."',";}
		
		else 							 { $sqlupd .= "pubplace	= '".$valor_null."',";}
		
		$sqlupd .= "pubdate 		= '".$_POST['c_pubdate'] 		."',";
		$sqlupd .= "edition 		= '".$_POST['c_edition'] 		."',";
		$sqlupd .= "escala 			= '".$_POST['c_escala'] 		."',";
		$sqlupd .= "clave 			= '".$_POST['c_clave'] 			."',";
		$sqlupd .= "issue 			= '".$_POST['c_issue'] 			."',";
		$sqlupd .= "resumen 		= '".$_POST['c_resumen'] 		."',";
		$sqlupd .= "abstract 		= '".$_POST['c_abstract'] 		."',";
		$sqlupd .= "objetivo 		= '".$_POST['c_objetivo'] 		."',";
		$sqlupd .= "datos_comp 		= '".$_POST['c_datos_comp'] 	."',";
		$sqlupd .= "tiempo 			= '".$_POST['c_tiempo'] 		."',";
		$sqlupd .= "tiempo2			= '".$_POST['c_tiempo2'] 		."',";
		$sqlupd .= "avance			= '".$_POST['c_avance'] 		."',"; 
		$sqlupd .= "mantenimiento 	= '".$_POST['c_mantenimiento'] 	."',"; 
								
		if (!empty($_POST['c_tamano'])) { $sqlupd .= "tamano 			= '".$_POST['c_tamano'] 		."',"; }
		else { $sqlupd .= "tamano			= '".$null_numeric."',"; }
		
		$sqlupd .= "fecha_inicial 	= '".$_POST['c_fecha_inicial'] 	."',";
		$sqlupd .= "fecha 			= '".$_POST['c_fecha'] 			."',";
		$sqlupd .= "version 		= '".$_POST['c_version'] 		."',";
		$sqlupd .= "geoform 		= '".$_POST['c_geoform'] 		."'";
		$sqlupd .= " WHERE record_id = '" .$record_id 				."'";
		$sql_div1 =  pg_query($db, $sqlupd);
		if (!$sql_div1) { exit("Error al actualizar la información del div 1"); } 
		
		
		/// ACTUALIZA LOS AUTORES
		
		$delete_origin =  pg_query($db, "DELETE FROM autores WHERE dataset_id = '".$record_id."'");
		$x_origin = $_POST['x_origin'];
		foreach ($x_origin as $key => $value) 
		{
			if ($value <> "" ) 
			{
				$sql_origin =  "INSERT INTO autores (dataset_id, origin) VALUES ('" .$record_id. "','" .$value."')";
				$res_origin =  pg_query($db, $sql_origin);
				if (!$res_origin) { exit("Error al actualizar la información de autores del div 1"); }
			}
		}
				
		/// ACTUALIZA LAS LIGAS
		
		$delete_ligaWWW =  pg_query($db, "DELETE FROM ligas_www WHERE dataset_id = '".$record_id."'");
		$l_liga_www = $_POST['l_liga_www'];
		foreach ($l_liga_www as $key => $value) 
		{
			if ($value <> "" ) 
			{
				$sql_ligaWWW =  "INSERT INTO ligas_www (dataset_id, liga_www) VALUES ('" .$record_id. "','" .$value."')";
				$res_ligaWWW =  pg_query($db, $sql_ligaWWW);
				if (!$res_ligaWWW) { exit("Error al actualizar la información de ligas del div 1"); }
			}
		}
				
				
	} break;
	
	case "restricciones":
	{
		//<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 3 >>>>>>>>>>>>>
		$sqlupd3  = "UPDATE coberturas SET ";  
		$sqlupd3 .= "acceso 			= '".$_POST['c_acceso'] 		."',";
		$sqlupd3 .= "uso 				= '".$_POST['c_uso'] 			."',";
		$sqlupd3 .= "observaciones 		= '".$_POST['c_observaciones'] 	."' ";
		$sqlupd3 .= " WHERE record_id 	= '" .$record_id 				."'";
				
		$sql_div3 =  pg_query($db, $sqlupd3);
		if (!$sql_div3) { exit("Error al actualizar la información del div 2"); }
	} break;
	
	case "palabrasClave":
	{
	  //<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 4 >>>>>>>>>>>>>
	 /// ACTUALIZA LOS TEMAS
			
		$delete_Palabra_Clave =  pg_query($db, "DELETE FROM temas_clave WHERE dataset_id = '".$record_id."'");
		$Palabra_Clave = $_POST['m_Palabra_Clave'];
		foreach ($Palabra_Clave as $key => $value) 
		{
			if ($value <> "" ) 
			{
				$sql_clave =  "INSERT INTO temas_clave (dataset_id, palabra_clave) VALUES ('" .$record_id. "','" .$value."')";
				$res_clave =  pg_query($db, $sql_clave);
				if (!$res_clave) { exit("Error al actualizar la información de temas del div 3"); }
			}
		}
				
		/// ACTUALIZA LOS SITIOS
		
		$delete_Sitios_Clave =  pg_query($db, "DELETE FROM sitios_clave WHERE dataset_id = '".$record_id."'");
		$Sitios_Clave = $_POST['s_Sitios_Clave'];
		foreach ($Sitios_Clave as $key => $value) 
		{
			if ($value <> "" ) 
			{
				$sql_sitios =  "INSERT INTO sitios_clave (dataset_id, sitios_clave) VALUES ('" .$record_id. "','" .$value."')";
				$res_sitios =  pg_query($db, $sql_sitios);
				if (!$res_sitios) { exit("Error al actualizar la información de sitios del div 3"); }
			}
		}
	} break;
	
	case "ambienteDeTrabajo":
	{
	  //<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 5 >>>>>>>>>>>>>
	
		$sqlupd5  = "UPDATE coberturas SET ";  
		$sqlupd5 .= "software_hardware 	= '".$_POST['c_software_hardware'] 	."', ";
		$sqlupd5 .= "sistema_operativo 	= '".$_POST['c_sistema_operativo'] 	."', ";
		$sqlupd5 .= "tecnicos 			= '".$_POST['c_tecnicos'] 			."', ";
		$sqlupd5 .= "path 				= '".$_POST['c_path'] 				."' ";
		$sqlupd5 .= " WHERE record_id 	= '" .$record_id 					."'";
		
		$sql_div5 =  pg_query($db, $sqlupd5);
		if (!$sql_div5) { exit("Error al actualizar la información del div 4"); }
	} break;
	
	case "calidadDeDatos":
	{
	  	//<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 6 >>>>>>>>>>>>>
		$sqlupd6  = "UPDATE coberturas SET ";  
		$sqlupd6 .= "metodologia 			= '".$_POST['c_metodologia'] 		."', ";
		$sqlupd6 .= "descrip_metodologia 	= '".$_POST['c_descrip_metodologia']."', ";
		$sqlupd6 .= "descrip_proceso 		= '".$_POST['c_descrip_proceso'] 	."' ";
		$sqlupd6 .= " WHERE record_id 	= '" .$record_id 						."'";
			
		$sql_div6 =  pg_query($db, $sqlupd6);
		if (!$sql_div6) { exit("Error al actualizar la información del div 6"); }
		
		$nombre	 	= $_POST['d_nombre']; 
		$publish 	= $_POST['d_publish'];
		$siglas 	= $_POST['d_siglas'];
		$pubplace 	= $_POST['d_pubplace'];
		$edition 	= $_POST['d_edition'];
		$escala 	= $_POST['d_escala'];
		$pubdate 	= $_POST['d_pubdate'];
		$formato 	= $_POST['d_formato'];
		$geoform 	= $_POST['d_geoform'];
		$srccontr 	= $_POST['d_srccontr'];
		$issue 		= $_POST['d_issue'];
		$onlink 	= $_POST['d_onlink'];
		$idorigen 	= $_POST['d_idorigen'];
		$idorigen0 	= $_POST['d_idorigen'];
		
		$val_maxId = 0;
		$array_datos = array();
		$array_origen = array();
		
		
		$sql_buscaDatos = pg_query($db, "SELECT id_origen FROM datos_origen  WHERE dataset_id = '".$record_id."' ORDER BY id_origen ASC");
		while ($fila = pg_fetch_array($sql_buscaDatos, null, PGSQL_ASSOC))	{	$array_datos [] = $fila ["id_origen"];	}
		$sql_buscaMax = "SELECT id_origen FROM datos_origen  ORDER BY id_origen DESC LIMIT 1";
		
		foreach ($idorigen as $fila => $valor)
		{
			$array_origen [] = $idorigen[$fila];
			if ($valor != 0)
			{
				$sqlupd_d6  = "UPDATE datos_origen SET ";  
				$sqlupd_d6 .= "nombre 			= '".$nombre[$fila] 	."',";
				$sqlupd_d6 .= "publish 			= '".$publish[$fila] 	."',";
				$sqlupd_d6 .= "publish_siglas 	= '".$siglas[$fila]  	."',";
				$sqlupd_d6 .= "pubplace 		= '".$pubplace[$fila]  	."',";
				$sqlupd_d6 .= "edition			= '".$edition[$fila]  	."',";
				$sqlupd_d6 .= "escala_original	= '".$escala[$fila]  	."',";
				$sqlupd_d6 .= "pubdate			= '".$pubdate[$fila]  	."',";
				$sqlupd_d6 .= "formato_original	= '".$formato[$fila]  	."',";
				$sqlupd_d6 .= "geoform 			= '".$geoform[$fila]  	."',";
				$sqlupd_d6 .= "srccontr 		= '".$srccontr[$fila]  	."',";
				$sqlupd_d6 .= "issue 			= '".$issue[$fila]  	."',";
				$sqlupd_d6 .= "onlink 			= '".$onlink[$fila] 	."'";
				$sqlupd_d6 .= "WHERE id_origen 	= '".$valor 			."'";
					
				$sql_d6 =  pg_query($db, $sqlupd_d6);
				if (!$sql_d6) { exit("Error al actualizar la información del div 5 tabla_d"); }

				// ACTUALIZA LOS AUTORES
				
				$delete_origin =  pg_query($db, "DELETE FROM autores_origen WHERE id_origen = '".$valor."'");
				$campo_origin 	= "h_origin".$valor;
				$h_origin		=  $_POST[$campo_origin];
					
				foreach ($h_origin as $key => $value) 
				{
					if ($value <> "" ) 
					{
						$sql_origin =  "INSERT INTO autores_origen (id_origen, origin) VALUES ('" .$valor. "','" .$value."')";
						$res_origin =  pg_query($db, $sql_origin);
						if (!$res_origin) { exit("Error al actualizar la información de autores del div 5"); }
					}
				}
			} // fin if ($valor <> 0)
		}// FIN foreach ($idorigen as $fila => $valor) 
		
		$diff_datos = array_diff($array_datos , $array_origen);
				
		if (count ($diff_datos) <> 0)
		{
			foreach ($diff_datos as $fila_d => $valor)
			{
				$delete_autores = pg_query ($db ,"DELETE FROM autores_origen WHERE id_origen = '".$diff_datos [$fila_d]."'");
				if (!$delete_autores) { exit("Error al eliminar autores_origen"); }
				
				$delete_datos = pg_query ($db ,"DELETE FROM datos_origen WHERE id_origen = '".$diff_datos [$fila_d]."'");
				if (!$delete_datos) { exit("Error al eliminar datos"); }
			}
		}
		
		foreach ($idorigen as $fila_0 => $valor_0)
		{
			if ($valor_0 == 0 && $nombre[$fila_0] <> "")
			{
				$res_buscaMax = pg_query($db, $sql_buscaMax);
				if (pg_num_rows ($res_buscaMax) <> "")
				{
					while ($fila = pg_fetch_array($res_buscaMax)) 
					{
						$val_maxId = $fila [0];
						$val_maxId = $val_maxId + 1; 
					}
				}
				else {$val_maxId = $val_maxId + 1;}
				
				$sqlupd_d6  = "INSERT INTO datos_origen (id_origen, dataset_id , nombre, publish, publish_siglas, pubplace,edition, escala_original, pubdate, formato_original, geoform, srccontr, issue, onlink) VALUES ( ";
				$sqlupd_d6 .= " ".$val_maxId.		" , ";
				$sqlupd_d6 .= " ".$record_id.		" , ";
				
				if  ( $nombre[$fila_0] <> "")	{	$sqlupd_d6 .= " '".$nombre[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $publish[$fila_0] <> ""){	$sqlupd_d6 .= " '".$publish[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $siglas[$fila_0] <> "")	{	$sqlupd_d6 .= " '".$siglas[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $pubplace[$fila_0] <> ""){	$sqlupd_d6 .= " '".$pubplace[$fila_0]."',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $edition[$fila_0] <> ""){	$sqlupd_d6 .= " '".$edition[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
					
				if  ( $escala[$fila_0] <> "")	{	$sqlupd_d6 .= " '".$escala[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $pubdate[$fila_0] <> ""){	$sqlupd_d6 .= " '".$pubdate[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $formato[$fila_0] <> ""){	$sqlupd_d6 .= " '".$formato[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $geoform[$fila_0] <> ""){	$sqlupd_d6 .= " '".$geoform[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $srccontr[$fila_0] <> ""){	$sqlupd_d6 .= " '".$srccontr[$fila_0]."',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
							
				if  ( $issue[$fila_0] <> "")	{	$sqlupd_d6 .= " '".$issue[$fila_0].	"',  "; }
				else 						{	$sqlupd_d6 .= " null,	";	}
						
				if  ( $onlink[$fila_0] <> "")	{	$sqlupd_d6 .= " '".$onlink[$fila_0].	"'  "; 	}
				else 						{	$sqlupd_d6 .= "null";						}
				
				$sqlupd_d6 .= " ) ";
				$sql_d6 =  pg_query($db, $sqlupd_d6);
				if (!$sql_d6) { exit("Error al insertar la informacion del div 5 tabla_d"); }
//				
//				// ACTUALIZA LOS AUTORES
//				
//				//$delete_origin =  pg_query($db, "DELETE FROM autores_origen WHERE id_origen = '".$valor_0."'");
				$c_origin 	= "h_origin".$valor_0;
				$origin		=  $_POST[$c_origin];
				
				echo "id maximo ".$val_maxId. " => " .$nombre[$fila_0]. "<br>";
				
				foreach ($origin as $key_0 => $value_origin) 
				{				
					if ($valor_0 == 0 && $value_origin <> "" ) 
					{
						echo "autores => ".$value_origin. "<br>";
////						$sql_origin_0 =  "INSERT INTO autores_origen (id_origen, origin) VALUES ('" .$val_maxId. "','" .$value_origin."')";
////						$res_origin_0 =  pg_query($db, $sql_origin_0);
////						if (!$res_origin_0) { exit("Error al actualizar la información de autores del div 6"); }
					}
				}
				
				
			}
		}// FIN foreach ($idorigen as $fila => $valor) 	018009056789
				
				
	} break;
	
	case "taxonomia":
	{
		$taxon 		= $_POST["t_taxon"]; 
		$reino		= $_POST["t_reino"];
		$division 	= $_POST["t_division"];
		$clase 		= $_POST["t_clase"];
		$orden 		= $_POST["t_orden"];
		$familia 	= $_POST["t_familia"];
		$genero 	= $_POST["t_genero"];
		$especie 	= $_POST["t_especie"];
		$nomcom 	= $_POST["t_nombre_comun"];
		$idtax 		= $_POST["t_idtax"];
		
		$sql_DatosTaxonomia = pg_query($db, "SELECT id_taxon FROM taxonomia  WHERE dataset_id = '".$record_id."' ORDER BY id_taxon ASC");
		if (!$sql_DatosTaxonomia) { exit("Error al buscar valores a la base de datos de taxonom&iacute;a"); }
		
		$array_taxonomia = array();
		$array_origenTaxonomia = array();
		
		if (pg_num_rows ($sql_DatosTaxonomia) <> "") {	while ($fila = pg_fetch_array($sql_DatosTaxonomia, null, PGSQL_ASSOC))	{	$array_taxonomia [] = $fila ["id_taxon"];	}}
		else { $array_taxonomia [] = 0;}
		
		$val_maxIdTaxon = 0;
		
		$sql_buscaMaxTaxon = pg_query($db, "SELECT id_taxon FROM taxonomia  ORDER BY id_taxon DESC LIMIT 1");
		if (!$sql_buscaMaxTaxon) { exit("Error al buscar el valor m&aacute;ximo del div 7 a la base de taxonom&iacute;a"); }
				
		
		if (pg_num_rows ($sql_buscaMaxTaxon) <> "")	
		{			
			while ($fila = pg_fetch_array($sql_buscaMaxTaxon)) 
			{
				$val_maxIdTaxon = $fila [0]; 
				$val_maxIdTaxon = $val_maxIdTaxon + 1;
			}
		}
		else { $val_maxIdTaxon = $val_maxIdTaxon + 1; }
		
		foreach ($idtax as $fila => $valor)
		{	
			if ($taxon [$fila] <> "")
			{
				if (is_numeric($valor))
				{
					$array_origenTaxonomia [] = $idtax[$fila];
					
					$sqlupd_d8  = "UPDATE taxonomia SET ";  
					$sqlupd_d8 .= "cobertura		= '".$taxon[$fila] 		."',";
					$sqlupd_d8 .= "reino 			= '".$reino[$fila] 		."',";
					$sqlupd_d8 .= "division			= '".$division[$fila] 	."',";
					$sqlupd_d8 .= "clase			= '".$clase[$fila]  	."',";
					$sqlupd_d8 .= "orden 			= '".$orden[$fila]  	."',";
					$sqlupd_d8 .= "familia			= '".$familia[$fila]  	."',";
					$sqlupd_d8 .= "genero			= '".$genero[$fila]  	."',";
					$sqlupd_d8 .= "especie			= '".$especie[$fila]  	."',";
					$sqlupd_d8 .= "nombre_comun		= '".$nomcom[$fila] 	."'";
					$sqlupd_d8 .= "WHERE id_taxon 	= '".$valor 			."'";
							
					$sql_d8 =  pg_query($db, $sqlupd_d8);
					if (!$sql_d8) { exit("Error al actualizar la informaci&oacute;n taxon&oacute;mica del div 6"); }
					
					$campo_n =  "g_title".$valor; 
					$campo_p =  "g_publish".$valor;
					$campo_s =  "g_siglas".$valor;
					$campo_c =  "g_pubplace".$valor;
					$campo_e =  "g_edition".$valor;
					$campo_d =  "g_pubdate".$valor;
					$campo_r =  "g_sername".$valor;
					$campo_i =  "g_issue".$valor;
					$campo_id = "g_idtaxon".$valor;
					$campo_ia = "g_idautaxon".$valor;
					
					$nombrec 	= $_POST[$campo_n]; 
					$publish 	= $_POST[$campo_p]; 
					$siglas 	= $_POST[$campo_s]; 
					$pubplace 	= $_POST[$campo_c]; 
					$edition 	= $_POST[$campo_e]; 
					$pubdate 	= $_POST[$campo_d]; 
					$sername 	= $_POST[$campo_r]; 
					$issue 		= $_POST[$campo_i]; 
					$idtaxon 	= $_POST[$campo_id]; 
					$idautaxon = $_POST[$campo_ia]; 
					
					$sql_DatosTaxoncita = pg_query($db, "SELECT idau_taxon FROM taxon_cita  WHERE id_taxon = '".$valor."' ORDER BY idau_taxon ASC");
					if (!$sql_DatosTaxoncita) { exit("Error al buscar valores a la base de datos de taxon_cita"); }
					
					$array_taxon_cita = array();
					$array_origenTaxon_cita = array();
					
					if (pg_num_rows ($sql_DatosTaxoncita) <> ""){	while ($fila = pg_fetch_array($sql_DatosTaxoncita, null, PGSQL_ASSOC))	{	$array_taxon_cita [] = $fila ["idau_taxon"];}	}
					else			{$array_taxon_cita [] = 0; }		
							 
					$sql_buscaMaxTc = pg_query($db, "SELECT idau_taxon FROM taxon_cita  ORDER BY idau_taxon DESC LIMIT 1");
					 if (!$sql_buscaMaxTc) { exit("Error al Buscar el valor maximo del div 6 a la base de citas taxon&oacute;micas"); }
									
					$val_maxIdTc = 0;
					
					if (pg_num_rows ($sql_buscaMaxTc) <> "")
					{
						 while ($fila = pg_fetch_array($sql_buscaMaxTc)) 
						 {
							$val_maxIdTc = $fila [0]; 
							$val_maxIdTc = $val_maxIdTc + 1;
						 }
					}
							
					else { $val_maxIdTc = $val_maxIdTc + 1; }
					
					foreach ($idautaxon as $fila => $valor_c)
					{
						if ($nombrec [$fila] <> "")
						{
							if (is_numeric($valor_c))
							{
								$array_origenTaxon_cita [] = $idautaxon[$fila];
										
								$sqlupd_d8tc  = "UPDATE taxon_cita SET ";  
								$sqlupd_d8tc .= "title			= '".$nombrec[$fila] 	."',";
								$sqlupd_d8tc .= "publish 		= '".$publish[$fila] 	."',";
								$sqlupd_d8tc .= "publish_siglas	= '".$siglas[$fila] 	."',";
								$sqlupd_d8tc .= "pubplace		= '".$pubplace[$fila]  	."',";
								$sqlupd_d8tc .= "edition 		= '".$edition[$fila]  	."',";
								$sqlupd_d8tc .= "pubdate		= '".$pubdate[$fila]  	."',";
								$sqlupd_d8tc .= "sername		= '".$sername[$fila]  	."',";
								$sqlupd_d8tc .= "issue			= '".$issue[$fila] 		."'";
								$sqlupd_d8tc .= "WHERE idau_taxon = '".$valor_c			."'";
										
								$sql_d8tc =  pg_query($db, $sqlupd_d8tc);
								if (!$sql_d8tc) { exit("Error al actualizar la informaci&oacute;n de citas taxonomicas del div 6"); }
								$campo_v = "z_origin".$valor."_".$valor_c;										
										
								/// ACTUALIZA LOS AUTORES
				
								$delete_autorestc =  pg_query($db, "DELETE FROM autores_taxon WHERE idau_taxon = '".$valor_c."'");
								$z_origin = $_POST[$campo_v]; 
								foreach ($z_origin as $key => $value) 
								{
									if ($value <> "" ) 
									{
										$sql_zorigin =  "INSERT INTO autores_taxon (idau_taxon, origin) VALUES ('" .$valor_c. "','" .$value."')";
										$res_zorigin =  pg_query($db, $sql_zorigin);
										if (!$res_zorigin) { exit("Error al actualizar la información de autores de taxon_cita"); }
									}
								}
							}// fin if (is_numeric($valor_c))
							
							if (!is_numeric($valor_c))
							{
										
								$sqlupd_d8tci  = "INSERT INTO taxon_cita (idau_taxon, id_taxon, title, publish, publish_siglas, pubplace, edition, pubdate, sername, issue) VALUES ( ";
								$sqlupd_d8tci .= " ".$val_maxIdTc.	" , ";
								$sqlupd_d8tci .= " ".$valor.		" , ";
								
								if  ( $nombrec[$fila] <> ""){	$sqlupd_d8tci .= " '".$nombrec[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $publish[$fila] <> ""){	$sqlupd_d8tci .= " '".$publish[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $siglas[$fila] <> "")	{	$sqlupd_d8tci .= " '".$siglas[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $pubplace[$fila] <> ""){	$sqlupd_d8tci .= " '".$pubplace[$fila]."',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $edition[$fila] <> ""){	$sqlupd_d8tci .= " '".$edition[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $pubdate[$fila] <> ""){	$sqlupd_d8tci .= " '".$pubdate[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $sername[$fila] <> ""){	$sqlupd_d8tci .= " '".$sername[$fila].	"',  "; }
								else 						{	$sqlupd_d8tci .= " null,	";	}
										
								if  ( $issue[$fila] <> "")	{	$sqlupd_d8tci .= " '".$issue[$fila].	"'  "; 	}
								else 						{	$sqlupd_d8tci .= "null";						}
										
								$sqlupd_d8tci .= " ) ";
								$sql_dtci =  pg_query($db, $sqlupd_d8tci);
								if (!$sql_dtci) { exit("Error al insertar la informacion de citas taxon&oacute;micas en el div 6"); }
										
								$z_origin2 = "z_origin".$valor."_".$valor_c;
								$autores_tc = $_POST[$z_origin2]; 
		
								foreach ($autores_tc as $key => $value) 
								{
									if ($value <> "" ) 
									{
										
										$sql_zorigin2 =  "INSERT INTO autores_taxon (idau_taxon, origin) VALUES ('" .$val_maxIdTc. "','" .$value."')";
										$res_zorigin2 =  pg_query($db, $sql_zorigin2);
										if (!$res_zorigin2) { exit("Error al insertar autores de taxon cita"); }
									}
								}
							}// FIN if (!is_numeric($valor_c))
						}// fin if ($nombrec [$fila] <> "")
					}// fin foreach ($idautaxon as $fila => $valor_c)
					
					$diff_datosTaxocita = array_diff($array_taxon_cita , $array_origenTaxon_cita);
					if (count ($diff_datosTaxocita) <> 0)
					{
						foreach ($diff_datosTaxocita as $fila => $valor)
						{
							$delete_originTc 	=  pg_query($db, "DELETE FROM autores_taxon WHERE idau_taxon = '".$valor."'");
							if (!$delete_originTc) { exit("Error al eliminar autores de taxonomia"); }
							
							$delete_Tcita 		=  pg_query($db, "DELETE FROM taxon_cita 	WHERE idau_taxon = '".$valor."'");
							if (!$delete_Tcita) { exit("Error al eliminar citas de taxonomia"); }
						}
					}
							
				}// fin if (is_numeric($valor))
				if (!is_numeric($valor))
				{
					$sqlupd_d8taxon  = "INSERT INTO taxonomia (id_taxon, dataset_id, cobertura, reino, division, clase, orden, familia, genero, especie, nombre_comun) VALUES ( ";
					$sqlupd_d8taxon .= " ".$val_maxIdTaxon.	 " , ";
					$sqlupd_d8taxon .= " ".$record_id.		 " , ";
					$sqlupd_d8taxon .= " '".$taxon[$fila].	 "', "; 
					$sqlupd_d8taxon .= " '".$reino[$fila].	 "', "; 
					$sqlupd_d8taxon .= " '".$division[$fila]."', "; 
					$sqlupd_d8taxon .= " '".$clase[$fila].	 "', "; 
					$sqlupd_d8taxon .= " '".$orden[$fila].	 "', "; 
					$sqlupd_d8taxon .= " '".$familia[$fila]. "', "; 
					$sqlupd_d8taxon .= " '".$genero[$fila].	 "', "; 
					$sqlupd_d8taxon .= " '".$especie[$fila]. "', "; 
					$sqlupd_d8taxon .= " '".$nomcom[$fila].	 "'  "; 	
					$sqlupd_d8taxon .= " ) ";
					$sql_dtaxon =  pg_query($db, $sqlupd_d8taxon);
					if (!$sql_dtaxon) { exit("Error al insertar nueva informacion taxon&oacute;mica en el div 7"); }
							
					$campo_n =  "g_title".$valor; 
					$campo_p =  "g_publish".$valor;
					$campo_s =  "g_siglas".$valor;
					$campo_c =  "g_pubplace".$valor;
					$campo_e =  "g_edition".$valor;
					$campo_d =  "g_pubdate".$valor;
					$campo_r =  "g_sername".$valor;
					$campo_i =  "g_issue".$valor;
					$campo_id = "g_idtaxon".$valor;
					$campo_ia = "g_idautaxon".$valor;
							
					$nombrec 	= $_POST[$campo_n]; 
					$publish 	= $_POST[$campo_p]; 
					$siglas 	= $_POST[$campo_s]; 
					$pubplace 	= $_POST[$campo_c]; 
					$edition 	= $_POST[$campo_e]; 
					$pubdate 	= $_POST[$campo_d]; 
					$sername 	= $_POST[$campo_r]; 
					$issue 		= $_POST[$campo_i]; 
					$idtaxon 	= $_POST[$campo_id]; 
					$idautaxon 	= $_POST[$campo_ia]; 
							 
							 
					$sql_buscaMaxTcita = pg_query($db, "SELECT idau_taxon FROM taxon_cita  ORDER BY idau_taxon DESC LIMIT 1");
					if (!$sql_buscaMaxTcita) { exit("Error al buscar el valor m&aacute;ximo del div 6 a la base de citas taxon&oacute;micas"); }
								
					$val_maxIdTcita = 0;
							
					if (pg_num_rows ($sql_buscaMaxTcita) <> "")
					{
						while ($fila = pg_fetch_array($sql_buscaMaxTcita)) 
						{
							$val_maxIdTcita = $fila [0]; 
							$val_maxIdTcita = $val_maxIdTcita + 1;
						}
					}
							
					else {$val_maxIdTcita = $val_maxIdTcita + 1;}
							
					//echo "val_maxIdTcit: $val_maxIdTcita";
							
					foreach ($idautaxon as $fila => $valor_c)
					{
						if ($nombrec [$fila] <> "")
						{
							$sqlupd_d8tci  = "INSERT INTO taxon_cita (idau_taxon, id_taxon, title, publish, publish_siglas, pubplace, edition, pubdate, sername, issue) VALUES ( ";
							$sqlupd_d8tci .= " ".$val_maxIdTcita.	" , ";
							$sqlupd_d8tci .= " ".$val_maxIdTaxon.		" , ";
									
							if  ( $nombrec[$fila] <> ""){	$sqlupd_d8tci .= " '".$nombrec[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
										
							if  ( $publish[$fila] <> ""){	$sqlupd_d8tci .= " '".$publish[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
										
							if  ( $siglas[$fila] <> "")	{	$sqlupd_d8tci .= " '".$siglas[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
									
							if  ( $pubplace[$fila] <> ""){	$sqlupd_d8tci .= " '".$pubplace[$fila]."',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
										
							if  ( $edition[$fila] <> ""){	$sqlupd_d8tci .= " '".$edition[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
								
							if  ( $pubdate[$fila] <> ""){	$sqlupd_d8tci .= " '".$pubdate[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
										
							if  ( $sername[$fila] <> ""){	$sqlupd_d8tci .= " '".$sername[$fila].	"',  "; }
							else 						{	$sqlupd_d8tci .= " null,	";	}
										
							if  ( $issue[$fila] <> "")	{	$sqlupd_d8tci .= " '".$issue[$fila].	"'  "; 	}
							else 						{	$sqlupd_d8tci .= "null";						}
									
							$sqlupd_d8tci .= " ) ";
							$sql_dtci =  pg_query($db, $sqlupd_d8tci);
							if (!$sql_dtci) { exit("Error al insertar la informaci&oacute;n de citas taxon&oacute;micas en el div 6"); }
									
							$z_origin2 = "z_origin".$valor."_".$valor_c;
							$autores_tc = $_POST[$z_origin2]; 
		
							foreach ($autores_tc as $key => $value) 
							{
								if ($value <> "" ) 
								{
									
									$sql_zorigin2 =  "INSERT INTO autores_taxon (idau_taxon, origin) VALUES ('" .$val_maxIdTcita. "','" .$value."')";
									$res_zorigin2 =  pg_query($db, $sql_zorigin2);
									if (!$res_zorigin2) { exit("Error al insertar autores de taxon cita"); }
								}
							}
						}
					} // FIN foreach ($idautaxon as $fila => $valor_c)		
				}// fin if (!is_numeric($valor))
			}// fin if ($taxon [$fila] <> "")
		}// fin foreach ($idtax as $fila => $valor)
		
		$diff_datosTaxonomia = array_diff($array_taxonomia , $array_origenTaxonomia);
				
		if (count ($diff_datosTaxonomia) <> 0)
		{
			foreach ($diff_datosTaxonomia as $fila => $valor)
			{
				$sql_DatosTcita = pg_query($db, "SELECT idau_taxon FROM taxon_cita  WHERE id_taxon = '".$valor."' ORDER BY idau_taxon ASC");
				if (!$sql_DatosTcita) { exit("Error al buscar valores a la base de datos de taxon_cita"); }
				
				while ($fila = pg_fetch_array($sql_DatosTcita, null, PGSQL_ASSOC))	
				{	
					$delete_originTc 	=  pg_query($db, "DELETE FROM autores_taxon WHERE idau_taxon = '".$fila ["idau_taxon"]."'");
					if (!$delete_originTc) { exit("Error al eliminar autores de taxonom&iacute;a"); }
					
					$delete_Tcita 		=  pg_query($db, "DELETE FROM taxon_cita 	WHERE idau_taxon = '".$fila ["idau_taxon"]."'");
					if (!$delete_Tcita) { exit("Error al eliminar citas de taxonom&iacute;a"); }
				}
				
				
				$delete_datosTc = pg_query ($db ,"DELETE FROM taxonomia WHERE id_taxon = '".$valor."'");
				if (!$delete_datosTc) { exit("Error al eliminar datos de taxonom&iacute;a"); }
			}
		}
	}break;
	
	case "proyeccion":
	{		
		$sqlupd2  = "UPDATE coberturas SET ";  
		
		if ($_POST['c_estructura_dato'] == 'Vector'  OR  $_POST['c_estructura_dato'] == 'vector'){
			$sqlupd2 .= "id_proyeccion 	= '".$_POST['c_id_proyeccion']	."',";
			$sqlupd2 .= "datum 			= '".$_POST['c_datum'] 			."',";
			$sqlupd2 .= "elipsoide 		= '".$_POST['c_elipsoide'] 		."', ";
		}
		if ($_POST['c_estructura_dato'] == 'Raster'  OR  $_POST['c_estructura_dato'] == 'raster'){
		
			if  ( $_POST['c_id_proyeccion'] <> "")	{	$sqlupd2 .= "id_proyeccion 	= '".$_POST['c_id_proyeccion']	."',";}
			else 						 			{	$sqlupd2 .= "id_proyeccion 	= '',";}						
			if  ( $_POST['c_datum'] <> "")			{	$sqlupd2 .= "datum 			= '".$_POST['c_datum'] 			."',";}
			else 									{	$sqlupd2 .= "datum 			= '',";}				
			if  ( $_POST['c_elipsoide'] <> "")		{	$sqlupd2 .= "elipsoide 		= '".$_POST['c_elipsoide'] 		."', ";}
			else 									{	$sqlupd2 .= "elipsoide 		= '',";}
		}
		
		
		$sqlupd2 .= "area_geo 			= '".$_POST['c_area_geo'] 		."',";
		$sqlupd2 .= "estructura_dato 	= '".$_POST['c_estructura_dato']."',";
		$sqlupd2 .= "tipo_dato 			= '".$_POST['c_tipo_dato'] 		."',";
		$sqlupd2 .= "total_datos 		= '".$_POST['c_total_datos'] 	."',";
		$sqlupd2 .= "oeste 				= '".$_POST['c_oeste'] 			."',";
		$sqlupd2 .= "este 				= '".$_POST['c_este'] 			."',";
		$sqlupd2 .= "norte 				= '".$_POST['c_norte'] 			."',";
		$sqlupd2 .= "sur 				= '".$_POST['c_sur'] 			."' ";		
		$sqlupd2 .= " WHERE record_id 	= '" .$record_id 				."'";
		
		$sql_div2 =  pg_query($db, $sqlupd2);
		if (!$sql_div2) { exit("Error al actualizar la informaci&oacute;n del div 7"); }
		
		/// SI LA ESTRUCTURA ES RASTER
		if ($_POST['c_estructura_dato'] == 'Raster'  OR  $_POST['c_estructura_dato'] == 'raster')
		{
			$delete_raster = pg_query ($db ,"DELETE FROM raster WHERE record_id = '".$record_id."'");
			if (!$delete_raster) { exit("Error al eliminar datos raster del div 7"); } 
		
			$sqlupd_d8rast  = "INSERT INTO raster (record_id, nun_renglones, num_columnas, coor_x , coor_y, pixel_x, pixel_y) VALUES ( ";
			$sqlupd_d8rast .= " '".$record_id.					"', ";
			$sqlupd_d8rast .= " '".$_POST['r_nun_renglones'].	"', ";
			$sqlupd_d8rast .= " '".$_POST['r_num_columnas'].	"', "; 
			$sqlupd_d8rast .= " '".$_POST['r_COOR_X'].			"', "; 
			$sqlupd_d8rast .= " '".$_POST['r_COOR_Y'].			"', "; 
			$sqlupd_d8rast .= " '".$_POST['r_pixel_X'].			"', "; 
			$sqlupd_d8rast .= " '".$_POST['r_pixel_Y'].			"'  "; 	
			$sqlupd_d8rast .= " ) ";
			$sql_raster =  pg_query($db, $sqlupd_d8rast);
			if (!$sql_raster) { exit("Error al insertar la informacion raster en el div 7"); }
		}
		
	}break;
	
	case "atributos":
	{
		//<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 10 >>>>>>>>>>>>>
		$sqlupd10 = "UPDATE coberturas SET ";  
		$sqlupd10 .= "tabla 			= '".$_POST['c_tabla']			."',";
		$sqlupd10 .= "descrip_tabla 	= '".$_POST['c_descrip_tabla'] 	."' ";
		$sqlupd10 .= " WHERE record_id = '" .$record_id 				."'";
			
		$sql_div10 =  pg_query($db, $sqlupd10);
		if (!$sql_div10) { exit("Error al actualizar la información de del div 8"); }
		
		$nombrea = $_POST['a_nombre'];
		$descrpa = $_POST['a_descipcion_atributo'];
		$fuentea = $_POST['a_fuente'];
		$unidaa = $_POST['a_unidades'];
		$tipoa = $_POST['a_tipo'];
				
		$delete_atributos = pg_query ($db ,"DELETE FROM atributos WHERE dataset_id = '".$record_id."'");
		if (!$delete_atributos) { exit("Error al eliminar atributos del div 8"); } 
					
		foreach ($nombrea as $fila => $value) 
		{
			if ($value <> "" ) 
			{
							
				$sqlupd_d10  = "INSERT INTO atributos (dataset_id, nombre, descipcion_atributo, fuente, unidades, tipo) VALUES ( ";
				$sqlupd_d10 .= " ".$record_id.	" , ";
										
				if  ( $nombrea[$fila] <> ""){	$sqlupd_d10 .= "'".$nombrea[$fila]."',"; }
				else 						{	$sqlupd_d10 .= "null,";	}
												
				if  ( $descrpa[$fila] <> ""){	$sqlupd_d10 .= "'".$descrpa[$fila]."',"; }
				else 						{	$sqlupd_d10 .= "";	}
													
				if  ( $fuentea[$fila] <> ""){	$sqlupd_d10 .= "'".$fuentea[$fila]."',"; }
				else 						{	$sqlupd_d10 .= " null,	";	}
													
				if  ( $unidaa[$fila] <> "")	{	$sqlupd_d10 .= "'".$unidaa[$fila]."',"; }
				else 						{	$sqlupd_d10 .= " null,	";	}
												
				if  ( $tipoa[$fila] <> "")	{	$sqlupd_d10 .= "'".$tipoa[$fila].	"'"; 	}
				else 						{	$sqlupd_d10 .= "null";		}
											
				$sqlupd_d10 .= " ) ";
				$sql_div10 =  pg_query($db, $sqlupd_d10);
				if (!$sql_div10) { exit("Error al insertar la informaci&oacute;n  de atributos en el div 8"); }
			}
		}
	} break;
	
	case "arbol":
	{
		//<<<<<<<<<<<<<< ACTUALIZA LOS DATOS DEL DIV 11 >>>>>>>>>>>>>
			$sqlupd11 = "UPDATE coberturas SET ";  
			$sqlupd11 .= "clasificacion 	= '".$_POST['c_clasificacion']	."'";
			$sqlupd11 .= " WHERE record_id 	= '" .$record_id 				."'";
			
			$sql_div11 =  pg_query($db, $sqlupd11);
			if (!$sql_div11) { exit("Error al actualizar la información de del div 9"); }		
	} break;
		
	default:
}
		$mensajesAll = "";
			echo "<form id=\"frm_guardar\" name=\"frm_guardar\" method=\"post\" action=\"../Menu.php#$record_id\">";
				echo "<input type=\"hidden\" name=\"id_metadato\" value=\"$record_id\" />";
				echo "<input type=\"hidden\" name=\"cv_principal\" value=\"$cv_principal\" />";
				echo "<input type=\"hidden\" name=\"displayBlock\" value=\"$displayBlock\" />";
				echo "<input type=\"hidden\" name=\"msgs_actualiza\" value=\"$mensajesAll\" />";
			echo "</form>";
			echo "<script type=\"text/javascript\">";
				echo "document.frm_guardar.submit();";
			echo "</script>";
		
?>