<?php 
function sql ($campo,$id , $cv_principal)
{
	switch ($campo)
    {
		case "seleccion":		$array = array(	'SELECT "record_id" , "nombre" FROM coberturas  ORDER BY "nombre" ASC;',
												'SELECT a.record_id , a.nombre FROM coberturas as a JOIN analistas as b ON a.id_analista=b."idAnalista" WHERE b.id_auth0 = \''.$cv_principal.'\' ORDER BY a.nombre ASC;'); break;
														
		case "campos":			$array = array(	'SELECT * FROM coberturas  WHERE "record_id" = '.$id.';',
												'DELETE   FROM coberturas  WHERE "record_id" = '.$id.';',
												''); break;
														
		case "x_origin": 		$array = array(	'SELECT "origin" 	FROM autores 	 WHERE "dataset_id" = '.$id.' ORDER BY "origin" ASC;',
												"SELECT  origin 	FROM autores 	 WHERE  dataset_id  = ".$id." AND origin = '".$cv_principal."'",
												"INSERT INTO autores (dataset_id, origin) VALUES (".$id. ",'" .$cv_principal."')",
												"DELETE FROM autores WHERE dataset_id = '".$id."'",
												''); break;
											
		case "l_liga_www": 		$array = array( 'SELECT "liga_www"		FROM ligas_www WHERE "dataset_id" = '.$id.'ORDER BY "liga_www" ASC;',
												"SELECT  liga_www 		FROM ligas_www WHERE  dataset_id  = ".$id." AND liga_www = '".$cv_principal."'",
												"INSERT INTO ligas_www (dataset_id, liga_www) VALUES (".$id. ",'" .$cv_principal."')",
												"DELETE FROM ligas_www WHERE dataset_id = '".$id."'",
												''); break;
											
		case "estado":			$array = array(	'SELECT "cve_ent" , "nom_ent" FROM estados ORDER BY "nom_ent" ASC',
												'SELECT "pubplace_edo" , "pubplace_muni" , "pubplace_loc" FROM coberturas WHERE "record_id" = '.$id.';'); break;
											
		case "municipio": 		$array = array(	'SELECT "cve_mun", "nom_mun" FROM municipios WHERE "cve_ent" = '.$id.' ORDER BY "nom_mun" ASC'	,
												'SELECT "pubplace_muni"  FROM coberturas WHERE "record_id" = '.$id.';'); break;
		
		case "localidad":		$array = array(	'SELECT cve_loc, nom_loc FROM localidades  WHERE cve_mun = '.$id.' ORDER BY nom_loc ASC',	
												'SELECT "pubplace_loc"  FROM coberturas WHERE "record_id" = '.$id.';'); break;
												
		case "m_Palabra_Clave": $array = array(	'SELECT "palabra_clave" 	FROM temas_clave A WHERE "dataset_id" = '.$id.';',
											 	'DELETE 	FROM temas_clave  WHERE "dataset_id" = '.$id.';',
												''	); break;
		
		case "s_Sitios_Clave": 	$array = array(	'SELECT "sitios_clave" 	FROM sitios_clave  WHERE "dataset_id" = '.$id.';',
											 	'DELETE 	FROM sitios_clave  WHERE "dataset_id" = '.$id.';',
												''); break;
												
		case "d_nombre": 		$array = array(	"SELECT * 			FROM datos_origen WHERE dataset_id = '".$id."' ORDER BY id_origen ASC;",
	 											'SELECT  id_origen 	FROM datos_origen WHERE dataset_id = ' .$id.'  ;',
												'DELETE  FROM datos_origen  WHERE dataset_id = '.$id. ';',
												''); break;	
		
		case "h_origin": 		$array = array(	'SELECT  origin 	FROM autores_origen  WHERE "id_origen"='.$id.' ORDER BY origin ASC;',
												'SELECT  id_origen 	FROM autores_origen  WHERE id_origen = '.$id. ' GROUP BY  id_origen;',
												'DELETE  FROM autores_origen  WHERE id_origen = '.$id. ';',
												''); break;
		
		case "t_taxon":			$array = array(	'SELECT * FROM taxonomia WHERE  "dataset_id" = '.$id.'  ORDER BY "id_taxon" ASC;',
												'SELECT * FROM taxon_cita WHERE "id_taxon"='.$id.' ORDER BY "idau_taxon" ASC;',
												'SELECT "origin" 			FROM autores_taxon WHERE "idau_taxon"='.$id. ' ;',
												'SELECT  id_taxon FROM taxonomia WHERE dataset_id = '.$id. ' ;',
												'SELECT  idau_taxon FROM taxon_cita WHERE id_taxon = '.$id. ' ;',
												'SELECT  idau_taxon FROM autores_taxon WHERE idau_taxon = '.$id. ' GROUP BY  idau_taxon;',
												''); break;	
												
		case "delete_taxon": 	$array = array(	'DELETE  FROM autores_taxon WHERE idau_taxon 	= '.$id. ' ;',
												'DELETE  FROM taxon_cita 	WHERE id_taxon 		= '.$id. ' ;',
												'DELETE  FROM taxonomia 	WHERE dataset_id 	= '.$id. ' ;',
												''); break;	
												
		case "raster": 			$array = array(	'SELECT * 	FROM raster  WHERE "record_id" = '.$id.';',
											  	'DELETE 	FROM raster  WHERE "record_id" = '.$id.';',
												''); break;	
		
		 case "a_nombre": 		$array = array(	"SELECT *  FROM atributos WHERE dataset_id = '".$id."';",
												'DELETE 	FROM atributos  WHERE "dataset_id" = '.$id.';',
												''	); break;
		
		 case "carga_arbol":	$array = array( 'SELECT "clasificacion" 	FROM coberturas WHERE "record_id" = '.$id.' ;',
 		'SELECT "idNivel1", "Nivel1", "idnivel2", "Nivel2", "idNivel3", "Nivel3", "idNivel4", "Nivel4", "idNivel5", "Nivel5", "idNivel6", "Nivel6", "id" FROM clasificacion GROUP BY "idNivel1", "Nivel1", "idnivel2", "Nivel2", "idNivel3", "Nivel3", "idNivel4", "Nivel4", "idNivel5", "Nivel5", "idNivel6", "Nivel6", "id" ORDER BY "idNivel1", "idnivel2", "idNivel3", "idNivel4", "idNivel5", "idNivel6", "id";' ); break;
		
		
	 }
  return $array; 
}
?>