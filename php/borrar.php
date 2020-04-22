<?php 
	ini_set("display_errors", "on");
	header('Content-Type: text/html; charset=utf-8');
	require_once('conexion.php');
	require_once('sentencias.php');
	
	$db = conectar();
	
	
	if ($db)
	{
		$cv_principal = isset($_GET['cv_principal']) ? $_GET['cv_principal'] : null ;
		$id= isset($_POST['delete_metadato']) ? $_POST['delete_metadato'] : null ;
		
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE TAXONOMICA, TACON_CITAS Y AUTORES_TAXON <<<<<<<<<<<<<<<<<<<<<<<
		$sql_taxon = sql("t_taxon",$id ,$cv_principal);
		$res_taxon = pg_query($db, $sql_taxon [3]);
		if (!$res_taxon) { exit("Error de consulta|"); }
		
		if (pg_num_rows($res_taxon) != 0)
		{
			while ($fila = pg_fetch_array($res_taxon, null, PGSQL_ASSOC))	
			{
				$id_taxon = $fila ["id_taxon"];
				$sql_taxon_cita = sql("t_taxon",$id_taxon ,$cv_principal);
				$res_taxon_cita = pg_query($db, $sql_taxon_cita [4]);
				
				
				if (pg_num_rows($res_taxon_cita) != 0)
				{
					while ($fila = pg_fetch_array($res_taxon_cita, null, PGSQL_ASSOC))	
					{
						$idau_taxon = $fila ["idau_taxon"];
						$sql_autores_taxon = sql("t_taxon", $idau_taxon ,$cv_principal);
						$res_autores_taxon = pg_query($db, $sql_autores_taxon [5]);
						
						if (pg_num_rows($res_autores_taxon) != 0)
						{
							$delete_autores_taxon =  sql("delete_taxon",$fila ["idau_taxon"] ,$cv_principal);
							$autores_taxon = pg_query($db, $delete_autores_taxon [0]);
						}			
					}
				}
				$delete_taxon_cita =  sql("delete_taxon",$id_taxon ,$cv_principal);
				$taxon_cita = pg_query($db, $delete_taxon_cita [1]);				
			}
			
			$delete_taxonomia =  sql("delete_taxon",$id ,$cv_principal);
			$taxonomia  = pg_query($db, $delete_taxonomia [2]);
		} // fin if (pg_num_rows($result) != 0)
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE DATOS ORIGEN Y AUTORES_ORIGEN <<<<<<<<<<<<<<<<<<<<<<<
		
		$sql_datos_origen = sql("d_nombre",$id ,$cv_principal);
		$res_datos_origen = pg_query($db, $sql_datos_origen [1]);
		if (!$res_datos_origen) { exit("Error de consulta|"); }
		if (pg_num_rows($res_datos_origen) != 0)
		{
			while ($fila = pg_fetch_array($res_datos_origen, null, PGSQL_ASSOC))
			{
				$id_origen = $fila ["id_origen"];
				$sql_autores_origen = sql("h_origin",$id_origen ,$cv_principal);
				$res_autores_origen = pg_query($db, $sql_datos_origen [1]);
				
				if (pg_num_rows($res_autores_origen) != 0)
				{
					$delete_autores_origen =  sql("h_origin",$id_origen ,$cv_principal);
					$autores_origen = pg_query($db, $delete_autores_origen [2]);
				}
			}
			$delete_datos_origen =  sql("d_nombre",$id ,$cv_principal);
			$datos_origen  = pg_query($db, $delete_datos_origen [2]);	
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE RASTER <<<<<<<<<<<<<<<<<<<<<<<
		$sql_raster = sql("raster",$id ,$cv_principal);
		$res_raster = pg_query($db, $sql_raster [0]);
		if (!$res_raster) { exit("Error de consulta|"); }
		if (pg_num_rows($res_raster) != 0)
		{
			$sql_delete_raster = sql("raster",$id ,$cv_principal);
			$raster = pg_query($db, $sql_raster [1]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE AUTORES <<<<<<<<<<<<<<<<<<<<<<<
		$sql_autores = sql("x_origin",$id ,$cv_principal);
		$res_autores = pg_query($db, $sql_autores [0]);
		if (!$res_autores) { exit("Error de consulta|"); }
		if (pg_num_rows($res_autores) != 0)
		{
			$sql_delete_autores = sql("x_origin",$id ,$cv_principal);
			$autores = pg_query($db, $sql_delete_autores [3]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE DE LIGAS_WWW <<<<<<<<<<<<<<<<<<<<<<<
		$sql_ligas = sql("l_liga_www",$id ,$cv_principal);
		$res_ligas = pg_query($db, $sql_ligas [0]);
		if (!$res_ligas) { exit("Error de consulta|"); }
		if (pg_num_rows($res_ligas) != 0)
		{
			$sql_delete_ligas = sql("l_liga_www",$id ,$cv_principal);
			$ligas = pg_query($db, $sql_delete_ligas [3]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE DE TEMAS CLAVE <<<<<<<<<<<<<<<<<<<<<<<
		$sql_temas = sql("m_Palabra_Clave",$id ,$cv_principal);
		$res_temas = pg_query($db, $sql_temas [0]);
		if (!$res_temas) { exit("Error de consulta|"); }
		if (pg_num_rows($res_temas) != 0)
		{
			$sql_delete_temas = sql("m_Palabra_Clave",$id ,$cv_principal);
			$temas = pg_query($db, $sql_delete_temas [1]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE DE SITIOS CLAVE <<<<<<<<<<<<<<<<<<<<<<<
		$sql_sitios = sql("s_Sitios_Clave",$id ,$cv_principal);
		$res_sitios = pg_query($db, $sql_sitios [0]);
		if (!$res_sitios) { exit("Error de consulta|"); }
		if (pg_num_rows($res_sitios) != 0)
		{
			$sql_delete_sitios = sql("s_Sitios_Clave",$id ,$cv_principal);
			$sitios = pg_query($db, $sql_delete_sitios [1]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA LA PARTE DE ATRIBUTOS <<<<<<<<<<<<<<<<<<<<<<<
		$sql_atributos= sql("a_nombre",$id ,$cv_principal);
		$res_atributos = pg_query($db, $sql_atributos [0]);
		if (!$res_atributos) { exit("Error de consulta|"); }
		if (pg_num_rows($res_atributos) != 0)
		{
			$sql_delete_atributos = sql("a_nombre",$id ,$cv_principal);
			$atributos = pg_query($db, $sql_delete_atributos [1]);
		}
		
		/////////// >>>>>>>>>>>>>>>>>  ELIMINA EL METADATO <<<<<<<<<<<<<<<<<<<<<<<
		$sql_delete_metadato = sql("campos",$id ,$cv_principal);
		$metadato = pg_query($db, $sql_delete_metadato [1]);
		
		//header ("Location: ../Menu.php"); 
		$mensajesAll = "El metadato a sido eliminado.";
		if ( $mensajesAll != "" ) 
		{
			echo "<form id=\"frm_guardar\" name=\"frm_guardar\" method=\"post\" action=\"../Menu.php\">";
				echo "<input type=\"hidden\" name=\"cv_principal\" value=\"$cv_principal\" />";
				echo "<input type=\"hidden\" name=\"msgs_actualiza\" value=\"$mensajesAll\" />";
			echo "</form>";
			echo "<script type=\"text/javascript\">";
				echo "document.frm_guardar.submit();";
			echo "</script>";
		}
		
	}// fin if DB
?>