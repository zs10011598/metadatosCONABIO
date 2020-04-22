<?php
ini_set("display_errors", "on");
header('Content-Type: text/html; charset=utf-8'); 
require_once('sentencias.php');
require_once('conexion.php');


function generaCampos($name , $class , $tip ){
	if( $class == 'textarea' ){	generaTextArea($name , $tip );	}
	if( $class == 'autores' OR $class == 'ligas'){	tabla($divClass ,$titulo , $name, $class , 0 , 0 , 0);	}	
	if( $class == 'text' OR $class == 'fecha'){	generaInput($name , $class , $tip);	}
 }

function generaTextArea($name , $tip ){	echo '<textarea name="'.$name.'" id="'.$name.'"></textarea>'; toolTip ($tip);  }

function generaInput($name  , $class , $tip){	echo '<input type="text" name="'.$name.'"  id="'.$name.'" class="'.$class.'" />'; toolTip ($tip);  }

function generaSelect($tex , $name){
		echo '<select name= "'.$name.'" id= "'.$name.'">';
        echo '<option value="0">Seleccione '.$tex.' '.$name.'</option>';
        echo '</select>';
 }
	
function toolTip ($tip){	if($tip != ''){echo '<img title ="'.$tip.'" src="img/icono-ayuda.gif\"/>';} }

function tabla($divClass ,$titulo , $name, $class , $cv_principal , $id , $db){
	$campo_v = $name."[]";
	echo  '<div id="'.$divClass.'">';
	
		if ($class == 'autores') {echo  $titulo.'<table class="'.$name.'" id="'.$name.'" width="100%" border="0">';}
		if ($class == 'ligas' || $class =='Temas' || $class =='Sitios') {echo  '<table class="'.$name.'" id="'.$name.'" width="100%" border="0">';;}
		echo  "<thead>";
   			echo  "<tr>";
   				echo  "<th>  </th>";
   			echo  "</tr>";
   		echo  "</thead>";
		echo  '<tbody >';
			
			if($id != 0)	
			{
				$sql_tabla = sql($name,$id , $cv_principal );
				$res_tabla = pg_query($db, $sql_tabla [0]);
				while ($fila = pg_fetch_array($res_tabla, null, PGSQL_ASSOC)) 
				{ 
					foreach ($fila as $valor_tabla) 
					{ 
						echo  '<tr>';
							echo  '<td><input type="text" name="'.$campo_v.'"  value= "'.$valor_tabla.'" id="'.$name.'"  class="clsAnchoTotal"/></td>';
							echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
						echo  "</tr>";
					}
				}
				
			}
			
			echo  '<tr>';
				echo  '<td><input type="text" name="'.$campo_v.'"  id="'.$name.'"  class="clsAnchoTotal"/></td>';
				echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
			echo  "</tr>";
			
		echo  "</tbody>";
		echo  "<tfoot>";
			echo  "<tr>";
				echo  '<td colspan="4" align="right">';
					echo  '<input type="button" value="Agregar '.$class.'" class="clsAgregarFila">';
				echo  "</td>";
			echo  "</tr>";
		echo  "</tfoot>";
		echo  '</table>';		
	echo '</div>';
  }

function tabla_d($divClass ,$titulo, $class , $cv_principal , $id , $db){
	
	echo  "<div id=$titulo>";
		echo  "<table id=$titulo border=\"10\" width=\"2350\">";   
			echo  "<thead>";
				echo  "<tr>";
					echo  "<th width=\"252\">T&iacute;tulo del dato</th>";
					echo  "<th width=\"234\">Instituci&oacute;n responsable</th>";
					echo  "<th width=\"144\">Siglas de la instituci&oacute;n</th>";
					echo  "<th width=\"144\">Lugar de publicaci&oacute;n</th>";
					echo  "<th width=\"144\">Versi&oacute;n</th>";
					echo  "<th width=\"144\">Escala</th>";
					echo  "<th width=\"144\">Fecha de publicaci&oacute;n</th>";
					echo  "<th width=\"144\">Formato del dato geoespacial</th>";
					echo  "<th width=\"144\">Tipo de dato geoespacial</th>";
					echo  "<th width=\"326\">Informaci&oacute;n</th>";
					echo  "<th width=\"144\">Otros</th>";
					echo  "<th width=\"144\">Link</th>";
					echo  "<th></th>";
					echo  "<th width=\"144\">Autores</th>";
					echo  "<th></th>";
				echo  "</tr>";
			echo  "</thead>";
			echo  "<tbody >"; 
				if ($id != 0)
				{
					$sql_tabla_d = sql("d_nombre" ,$id , $cv_principal);
					$res_tabla_d = pg_query($db, $sql_tabla_d[0]);
					if (pg_num_rows ($res_tabla_d)){
						while ($fila = pg_fetch_array($res_tabla_d, NULL, PGSQL_ASSOC))
						{
								 
							 echo  "<tr>";
								 echo  "<td><input type=\"text\" name=\"d_nombre[]	\" value= \"".$fila ['nombre'].			"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_publish[]	\" value= \"".$fila ['publish'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_siglas[]	\" value= \"".$fila ['publish_siglas'].	"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_pubplace[]\" value= \"".$fila ['pubplace'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_edition[]	\" value= \"".$fila ['edition'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_escala[]	\" value= \"".$fila ['escala_original']."\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_pubdate[]	\" value= \"".$fila ['pubdate'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_formato[]	\" value= \"".$fila ['formato_original']."\" class=\"$class\"/></td>";					  
								 echo  "<td><input type=\"text\" name=\"d_geoform[]	\" value= \"".$fila ['geoform'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_srccontr[]	\" value= \"".$fila ['srccontr'].		"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_issue[]	\" value= \"".$fila ['issue'].			"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_onlink[]	\" value= \"".$fila ['onlink'].			"\" class=\"$class\"/></td>";
								 echo  "<td><input type=\"hidden\" name=\"d_idorigen[]\" value= \"".$fila ['id_origen'].	"\" class=\"clsAnchoTotal\"/></td>";
								 tabla_da($fila ['id_origen'] , $cv_principal, $db);	
									 
								echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
							echo  "</tr>"; 
						} // FIN WHILE
					} // FIN if (pg_num_rows ($res_tabla_d))					
				} // FIN if ($id != 0)
							echo  "<tr>";
								 echo  "<td><input type=\"text\" name=\"d_nombre[]	\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_publish[]	\"  class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_siglas[]	\"  class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_pubplace[]\"  class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_edition[]	\"  class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_escala[]	\"  class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_pubdate[]	\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_formato[]	\" 	class=\"$class\"/></td>";					  
								 echo  "<td><input type=\"text\" name=\"d_geoform[]	\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_srccontr[]\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_issue[]	\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"text\" name=\"d_onlink[]	\" 	class=\"$class\"/></td>";
								 echo  "<td><input type=\"hidden\" name=\"d_idorigen[]\" value= \"0\" class=\"clsAnchoTotal\"/></td>";
								 tabla_da(0 , $cv_principal, $db);	
									 
								echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
							echo  "</tr>"; 		
			echo  "</tbody>";
			echo  "<tfoot>";
				echo  "<tr>";
					echo  "<td colspan=\"5\" align=\"right\">";
						echo  "<input type=\"button\" value=\"Agregar datos\" class=\"clsAgregarFilad\">";
					echo  "</td>";
				echo  "</tr>";
			echo  "</tfoot>";
		echo  "</table>";
	echo  "</div>";
 }

function tabla_da($val_ido , $cv_principal , $db){
	$campo = "h_origin";
  	$tamano = "30px";
  	$tema = "AutoresRef".$val_ido;
  	$campo_v = $campo."".$val_ido."[]";
    
  	echo  "<td>";
  		echo  "<div id=$tema>";
  			echo  "<table id=$tema>";
  				echo  "<thead>";
  					echo  "<tr>";
  						echo  "<th>  </th>";
  					echo  "</tr>";
  				echo  "</thead>";
  				echo  "<tbody>";
					
					if($val_ido != 0)
					{
						$sql_tabla_da = sql($campo,$val_ido , $cv_principal , 0);
						$res_tabla_da = pg_query($db, $sql_tabla_da[0]);
						while ($fila = pg_fetch_array($res_tabla_da, null, PGSQL_ASSOC)) 
						{ 
							foreach ($fila as $valor_tabla_da) 
							{ 
								echo  "<tr>";
									echo  "<td><input type=\"text\" name=\"$campo_v\" value= \"$valor_tabla_da\" class=\"clsAnchoTotal\"/></td>" ;
									echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
								echo  "</tr>";
							}
						}
					}
					
					echo  "<tr>";
						echo  "<td><input type=\"text\" name=\"$campo_v\"  class=\"clsAnchoTotal\"/></td>" ;
						echo  '<td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
					echo  "</tr>";
				

	  			echo  "</tbody>";
 	  			echo  "<tfoot>";
	    			echo  "<tr>";
	     				echo  "<td colspan=\"1\" align=\"right\">";
  	       					echo  "<input type=\"button\" value=\"Agregar autores\" class=\"clsAgregarFila\">";
   	     				echo  "</td>";
					echo  "</tr>";
	  			echo  "</tfoot>";
			echo  "</table>";
  		echo  "</div>";
	echo "</td>";
 }
 
function tabla_t($divClass ,$titulo, $class , $cv_principal , $id , $db){

	$taxon 		= "t_taxon"; 
 	$reino		= "t_reino";
 	$division 	= "t_division";
 	$clase 		= "t_clase";
 	$orden 		= "t_orden";
 	$familia 	= "t_familia";
 	$genero 	= "t_genero";
 	$especie 	= "t_especie";
 	$nomcom 	= "t_nombre_comun";
 	$idtax 		= "t_idtax";
 
 	$tcampo_t = $taxon."[]";
	$tcampo_r = $reino."[]";
	$tcampo_d = $division."[]";
	$tcampo_c = $clase."[]";
	$tcampo_o = $orden."[]";
	$tcampo_f = $familia."[]";
	$tcampo_g = $genero."[]";
	$tcampo_e = $especie."[]";
	$tcampo_n = $nomcom."[]";
	$tcampo_it = $idtax."[]";					
 	$tamano_id ="10px";

echo  "<div id=$titulo>";
 echo  "<table width=\"2000\" id=$titulo border=\"1\">";  
  echo  "<thead>";
   echo  "<tr>";
	 echo  "<th width=\"350\">Taxon</th>";
	 echo  "<th width=\"1561\">Cita</th>";		
   echo  "</tr>";
  echo  "</thead>";
  echo  "<tbody>";
  
  if($id!=0)
  {
  		$sql_tabla_t = sql("t_taxon",$id , $cv_principal);
		$res_tabla_t = pg_query($db, $sql_tabla_t[0]);
		while ($fila = pg_fetch_array($res_tabla_t, null, PGSQL_ASSOC))
		{
			$val_t 	= $fila ['cobertura'];
			$val_rt = $fila ['reino'];
			$val_d 	= $fila ['division'];
			$val_c 	= $fila ['clase'];
			$val_o 	= $fila ['orden'];
			$val_f 	= $fila ['familia'];
			$val_g 	= $fila ['genero'];
			$val_e 	= $fila ['especie'];
			$val_n	= $fila ['nombre_comun'];
			$val_it = $fila ['id_taxon'];
			
			echo  "<tr>";
			echo  "<td>"; 
			echo  "</tr>";
			echo  "</td>"; 
			echo  "<tr>";
			echo  "<td>";
					echo  "<table  width=\"350\">";
						echo  "<tr>";
							echo "<td width=\"140\" align=\"right\">Cobertura general:</td width=\"198\"> <td> 
																						<input type=\"text\" name=\"$tcampo_t\" class=\"$class\"  id= \"$taxon\"    value= \"$val_t\" /></td> </tr>";
							echo  "<tr><td align=\"right\">Reino:</td><td>				<input type=\"text\" name=\"$tcampo_r\" class=\"$class\"  id= \"$reino\"    value= \"$val_rt\"/></td></tr>";
							echo  "<tr><td align=\"right\">Divisi&oacute;n o fila:</td><td>	<input type=\"text\" name=\"$tcampo_d\" class=\"$class\"  id= \"$division\" value= \"$val_d\" /></td></tr>";
							echo  "<tr><td align=\"right\">Clase:</td><td>				<input type=\"text\" name=\"$tcampo_c\" class=\"$class\"  id= \"$clase\"    value= \"$val_c\" /></td></tr>";
							echo  "<tr><td align=\"right\">Orden:</td><td>				<input type=\"text\" name=\"$tcampo_o\" class=\"$class\"  id= \"$orden\"    value= \"$val_o\" /></td></tr>";
							echo  "<tr><td align=\"right\">Familia:</td><td>			<input type=\"text\" name=\"$tcampo_f\" class=\"$class\"  id= \"$familia\"  value= \"$val_f\" /></td></tr>";
							echo  "<tr><td align=\"right\">G&eacute;nero:</td><td>		<input type=\"text\" name=\"$tcampo_g\" class=\"$class\"  id= \"$genero\"   value= \"$val_g\" /></td></tr>";
							echo  "<tr><td align=\"right\">Especie:</td><td>			<input type=\"text\" name=\"$tcampo_e\" class=\"$class\"  id= \"$especie\"  value= \"$val_e\" /></td></tr>";
							echo  "<tr><td align=\"right\">Nombre com&uacute;n:</td><td><input type=\"text\" name=\"$tcampo_n\" class=\"$class\"  id= \"$nomcom\"	value= \"$val_n\" /></td></tr>";
							echo  "<td><input type=\"hidden\" name=\"$tcampo_it\" value= \"$val_it\"  class=\"$class\"/></td>"; // hidden
					echo  "</table>";
			echo  "</td>";
			echo  "<td>";  		
			tabla_tc($class ,$val_it,"cita taxon&oacute;mica",$cv_principal, $id , $db);	
			echo  "</td>";
			echo  "<td width=\"1\">";
					
			echo  '<td align=\"center\"><td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td></td>';
			echo  "</td>";
			echo  "</tr>";
		}
  }
  
  	echo  "<tr>";
		echo  "<td>";
			echo  "<table  width=\"350\">";
				echo  "<tr>";
				echo "<td width=\"140\" align=\"right\">Cobertura general:</td width=\"198\"> <td> 
																				<input type=\"text\" name=\"$tcampo_t\" class=\"$class\"  id= \"$taxon\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Reino:</td><td>					<input type=\"text\" name=\"$tcampo_r\" class=\"$class\"  id= \"$reino\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Divisi&oacute;n o fila:</td><td>	<input type=\"text\" name=\"$tcampo_d\" class=\"$class\"  id= \"$division\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Clase:</td><td>					<input type=\"text\" name=\"$tcampo_c\" class=\"$class\"  id= \"$clase\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Orden:</td><td>					<input type=\"text\" name=\"$tcampo_o\" class=\"$class\"  id= \"$orden\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Familia:</td><td>				<input type=\"text\" name=\"$tcampo_f\" class=\"$class\"  id= \"$familia\"	/></td></tr>";
				echo  "<tr><td align=\"right\">G&eacute;nero:</td><td>			<input type=\"text\" name=\"$tcampo_g\" class=\"$class\"  id= \"$genero\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Especie:</td><td>				<input type=\"text\" name=\"$tcampo_e\" class=\"$class\"  id= \"$especie\"	/></td></tr>";
				echo  "<tr><td align=\"right\">Nombre com&uacute;n:</td><td>	<input type=\"text\" name=\"$tcampo_n\" class=\"$class\"  id= \"$nomcom\"	/></td></tr>";
				echo  "<td><input type=\"hidden\" name=\"$tcampo_it\" value= \"t0\"  class=\"$class\"/></td>"; // hidden
			echo  "</table>";
			echo  "</td>";
			echo  "<td>";
			tabla_tc($class,"t0","cita taxon&oacute;mica",$cv_principal , $id , $db);
			echo  "</td>";
			echo  "<td width=\"1\">";
		
			echo  '<td align=\"center\"><td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td></td>';
		echo  "</td>";
	echo  "</tr>"; 	
  
  echo "</tbody>";
  echo  "<tfoot>";
   echo  "<tr>";
	echo  "<td colspan=\"1\" align=\"right\">";
	 echo  "<input type=\"button\" value=\"Agregar $titulo\" class=\"clsAgregarFilat\">";
	echo  "</td>";
   echo  "</tr>"; 
  echo  "</tfoot>";
 echo  "</table>";
 
echo  "</div>";
 }
 
function tabla_tc($class,$validt,$tema ,$cv_principal, $id , $db){
	$nombrec 	= "g_title".$validt; 
	$publish 	= "g_publish".$validt;
	$siglas 	= "g_siglas".$validt;
	$pubplace 	= "g_pubplace".$validt;
	$edition 	= "g_edition".$validt;
	$pubdate 	= "g_pubdate".$validt;
	$sername 	= "g_sername".$validt;
	$issue 		= "g_issue".$validt;
	$idtaxon 	= "g_idtaxon".$validt;
	$idautaxon 	= "g_idautaxon".$validt;
	 
	 
	$campo_n = $nombrec."[]";
	$campo_p = $publish."[]";
	$campo_s = $siglas."[]";
	$campo_c = $pubplace."[]";
	$campo_e = $edition."[]";
	$campo_d = $pubdate."[]";
	$campo_r = $sername."[]";
	$campo_i = $issue."[]";
	$campo_id = $idtaxon."[]";
	$campo_ia = $idautaxon."[]";
	$tamano_id = "8px";
	
	echo  "<div id=$tema>";
		echo  "<table id=$tema width=\"1550\">";
			echo  "<thead>";
				echo  "<tr>";
					echo  "<th width=\"244\">T&iacute;tulo</th>";
					echo  "<th width=\"244\">Instituci&oacute;n</th>";
					echo  "<th width=\"144\">Siglas</th>";
					echo  "<th width=\"144\">Lugar</th>";
					echo  "<th width=\"144\">Versi&oacute;n</th>";
					echo  "<th width=\"144\">Fecha</th>";
					echo  "<th width=\"144\">Clave</th>";
					echo  "<th width=\"144\">Descripci&oacute;n</th>";
					echo  "<th>   </th>";
					echo  "<th>   </th>";
					echo  "<th>   </th>";
					echo  "<th width=\"69\">Autores</th>";
				echo  "</tr>";
			echo  "</thead>";
			echo  "<tbody>";
			
				if($validt <> "t0")
				{
					$sql_tabla_tc = sql("t_taxon",$validt,$cv_principal );
					$res_tabla_tc = pg_query($db, $sql_tabla_tc[1]);
					while ($fila = pg_fetch_array($res_tabla_tc, null, PGSQL_ASSOC))
					{
						$val_n 	= $fila ['title'];
						$val_p 	= $fila ['publish'];
						$val_s 	= $fila ['publish_siglas'];
						$val_c 	= $fila ['pubplace'];
						$val_e 	= $fila ['edition'];
						$val_d 	= $fila ['pubdate'];
						$val_r 	= $fila ['sername'];
						$val_i 	= $fila ['issue'];
						$val_id = $fila ['id_taxon'];
						$val_ia = $fila ['idau_taxon'];
						
						echo  "<tr>";
							echo  "<td><input type=\"text\" name=\"$campo_n\" value= \"$val_n\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_p\" value= \"$val_p\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_s\" value= \"$val_s\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_c\" value= \"$val_c\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_e\" value= \"$val_e\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_d\" value= \"$val_d\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_r\" value= \"$val_r\" class=\"$class\"/></td>";
							echo  "<td><input type=\"text\" name=\"$campo_i\" value= \"$val_i\" class=\"$class\"/></td>";
							echo  "<td><input type=\"hidden\" name=\"$campo_id\" value= \"$val_id\" class=\"$class\"/></td>";  //hidden
							echo  "<td><input type=\"hidden\" name=\"$campo_ia\" value= \"$val_ia\" class=\"$class\"/></td>"; //hidden
							echo  '<td align=\"right\"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
							tabla_ta($class ,$val_ia,$validt,$cv_principal, $id , $db);
						echo  "</tr>";
					}
				}
			
				echo  "<tr>";
					echo  "<td><input type=\"text\" name=\"$campo_n\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_p\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_s\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_c\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_e\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_d\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_r\" class=\"$class\"/></td>";
					echo  "<td><input type=\"text\" name=\"$campo_i\" class=\"$class\"/></td>";
					echo  "<td><input type=\"hidden\" name=\"$campo_id\" value= \"t0\" class=\"$class\"/></td>";  //hidden
					echo  "<td><input type=\"hidden\" name=\"$campo_ia\" value= \"a0\" class=\"$class\"/></td><br>"; //hidden
					echo  '<td align=\"right\"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
					tabla_ta($class ,"a0",$validt,$cv_principal, $id , $db);
				echo  "</tr>";
			
			echo  "</tbody>";
			echo  "<tfoot>";
				echo  "<tr>";
					echo  "<td colspan=\"9\" align=\"right\">";
						echo  "<input type=\"button\" value=\"Agregar $tema\" class=\"clsAgregarFilatc\">";
					echo  "</td>";
				echo  "</tr>";
			echo  "</tfoot>";
		echo  "</table>";
	echo  "</div>";
	
 }
 
function tabla_ta($class,$val_ido, $validt,$cv_principal, $id , $db){
	$campo = "z_origin".$validt."_".$val_ido;
  	$tamano = "30px";
  	$teema = "AutoresTaxon";
  	$campo_v = $campo."[]";
	
	echo  "<td>";   
		echo  "<div id=$teema>";
			echo  "<table id=$teema>";
				echo  "<thead>";
					echo  "<tr>";
						echo  "<th>  </th>";
					echo  "</tr>";
				echo  "</thead>";
				echo  "<tbody>";
				
					if ($val_ido <> "a0")
					{
						$sql_tabla_ta = sql("t_taxon",$val_ido,$cv_principal);
						$res_tabla_ta = pg_query($db, $sql_tabla_ta[2]);
						while ($fila = pg_fetch_array($res_tabla_ta, null, PGSQL_ASSOC))
						{
							foreach ($fila as $valor) 
							{
								echo  "<tr>";
									echo  "<td><input type=\"text\" name=\"$campo_v\" value= \"$valor\" class=\"$class\"/></td>";
									echo  '<td align=\"right\"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
								echo  "</tr>"; 
							}
						}
						
					}
				
					echo  "<tr>";
						echo  "<td><input type=\"text\" name=\"$campo_v\" class=\"$class\"/></td>";
						echo  '<td align=\"right\"><img src="img/borrar.gif" class="clsEliminarFila"></td>';
					echo  "</tr>";
					
				echo  "</tbody>";
				echo  "<tfoot>";
					echo  "<tr>";
						echo  "<td colspan=\"1\" align=\"right\">";
							echo  "<input type=\"button\" value=\"Agregar autores\" class=\"clsAgregarFila\">";
						echo  "</td>";
					echo  "</tr>";
				echo  "</tfoot>";
			echo  "</table>";
	  	echo  "</div>";
	echo "</td>";
 }
 
function tabla_a($divClass , $cv_principal , $id , $db){
	 $nombre = "a_nombre"; 
	 $descrp = "a_descipcion_atributo";
	 $fuente = "a_fuente";
	 $unida = "a_unidades";
	 $tipo = "a_tipo";
	 $tema = "atributos";
	 $class = "extenso";

	$campo_n = $nombre."[]";
	$campo_d = $descrp."[]";
	$campo_f = $fuente."[]";
	$campo_u = $unida."[]";
	$campo_t = $tipo."[]";
	
	echo  "<div id=$tema>";
		echo  "<table id=$tema width=\"1269\">";
			echo  "<thead>";
				echo  "<tr>";
					echo  "<th width=\"352\">  </th>";
				echo  "</tr>";
			echo  "</thead>";
			echo  "<tbody>";
			
				if ($id != 0){
					$sql_tabla_a = sql($nombre,$id , $cv_principal);
					$res_tabla_a = pg_query($db, $sql_tabla_a[0]);
					
					while ($fila = pg_fetch_array($res_tabla_a, NULL, PGSQL_ASSOC))
						{
							$val_n = $fila ['nombre'];
							$val_d = $fila ['descipcion_atributo'];
							$val_f = $fila ['fuente'];
							$val_u = $fila ['unidades'];
							$val_t = $fila ['tipo'];
								
							echo  "<tr>";
							  echo  "<td><input type=\"text\" name=\"$campo_n\" value= \"$val_n\" class=\"$class\"/></td>";
							  echo  "<td width=\"396\"><input type=\"text\" name=\"$campo_d\" value= \"$val_d\" class=\"$class\"/></td>";
							  echo  "<td width=\"202\"><input type=\"text\" name=\"$campo_f\" value= \"$val_f\" class=\"$class\"/></td>";
							  echo  "<td width=\"153\"><input type=\"text\" name=\"$campo_u\" value= \"$val_u\" class=\"$class\"/></td>";
							  echo  "<td width=\"121\"><input type=\"text\" name=\"$campo_t\" value= \"$val_t\" class=\"$class\"/></td>";
							  echo  '<td align=\"center\"><td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td></td>';
							echo  "</tr>";
						}// FIN WHILE
				}
			
				echo  "<tr>";
						echo  "<td><input type=\"text\" name=\"$campo_n\" list=\"$nombre\"  class=\"$class\"/></td>";
						echo  "<td width=\"396\"><input type=\"text\" name=\"$campo_d\" class=\"$class\"/></td>";
						echo  "<td width=\"202\"><input type=\"text\" name=\"$campo_f\" class=\"$class\"/></td>";
						echo  "<td width=\"153\"><input type=\"text\" name=\"$campo_u\" class=\"$class\"/></td>";
						echo  "<td width=\"121\"><input type=\"text\" name=\"$campo_t\" class=\"$class\"/></td>";
						echo  '<td align=\"center\"><td align="left"><img src="img/borrar.gif" class="clsEliminarFila"></td></td>';
					echo  "</tr>";
				
			
			echo  "</tbody>";
			echo  "<tfoot>";
				echo  "<tr>";
					echo  "<td colspan=\"6\" align=\"right\">";
						echo  "<input type=\"button\" value=\"Agregar $tema\" class=\"clsAgregarFila\">";
					echo  "</td>";
				echo  "</tr>";
			echo  "</tfoot>";
		echo  "</table>";
	echo  "</div>";
 }
 
function crea_arbol($divClass , $cv_principal , $id ){
	$db = conectar();
	if ($db) 
	{

			$sql_arbol = sql("carga_arbol",$cv_principal  , $id  );
			$res_arbol = pg_query($db, $sql_arbol[0]);
			if (!$res_arbol) { exit("Error en la consulta"); }
			else
			{

				$val = 0;	

				$res_arbol2 = pg_query($db, $sql_arbol[1]);
				if (!$res_arbol2) { exit("Error en la consulta"); }
				else
				{	  
					echo "<ul id=\"tt\" class=\"easyui-tree\" data-options=\"lines:true\">";
						echo "<li id =\"0\"> <span>Acervo</span>";
							echo "<ul>";
								echo "<ul>";
											$niv = array(0,0,0,0,0,0,0);
		 									$cla = array("vvvvv","vvvvv","vvvvv","vvvvv","vvvvv","vvvvv");
											while ( $fila = pg_fetch_row($res_arbol2, null, PGSQL_ASSOC))
											{
												
												if ( $niv[5] <> 0 and $fila ['idNivel6'] == '')
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[5] = 0;
												} 
												if ( $niv[4] <> $fila ['idNivel5'] and $niv[5] <> 0)
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[5] = 0;
												}	
												if ( $niv[3] <> $fila ['idNivel4'] and $niv[4] <> 0)
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[4] = 0;
												}	
												if ( $niv[2] <> $fila ['idNivel3'] and $niv[3] <> 0)
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[3] = 0;
												}	
												if ( $niv[1] <> $fila ['idnivel2'] and $niv[2] <> 0)
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[2] = 0;
												}
												if ( $niv[0] <> $fila ['idNivel1'])
												{ 
													echo "</ul>";
													echo "</li>";
													$niv[1] = 0;
												}
																							
												$n_n[0] = $fila ['idNivel1'];
												$n_v[0] = $fila ['Nivel1'];
												$n_n[1] = $fila ['idnivel2']; 
												$n_v[1] = $fila ['Nivel2'];
												$n_n[2] = $fila ['idNivel3'];
												$n_v[2] = $fila ['Nivel3'];
												$n_n[3] = $fila ['idNivel4'];
												$n_v[3] = $fila ['Nivel4'];
												$n_n[4] = $fila ['idNivel5'];
												$n_v[4] = $fila ['Nivel5'];
												$n_n[5] = $fila ['idNivel6'];
												$n_v[5] = $fila ['Nivel6'];
												$niv[6] = $fila ['id'];
												
												if (($niv[0] <> $n_n[0]) or ($cla[0] <> $n_v[0]))   
												{
													 $cla[0] = $n_v[0];
													 $niv[0] = $n_n[0];
													 echo "<li id=\"$n_v[0]\" data-options=\"state:'closed'\"> ";
													 echo "<span>$n_v[0]</span>";
													 echo "<ul>";
												}  												
												if (($niv[1] <> $n_n[1]) or ($cla[1] <> $n_v[1]))
												{
													 $cla[1] = $n_v[1];
													 $niv[1] = $n_n[1];
												 
													if ($n_n[2] > 0) 
													{
														echo "<li id=\"$n_v[1]\" data-options=\"state:'closed'\">";
														echo "<span>$n_v[1]</span>";
														echo "<ul>";
													}  
													else 
													{
														echo "<li id=\"$niv[6]\">";
														echo "<span><a href=\"#\" style=\"text-decoration:none;color:black;\" ondblclick=\"getSelected()\">$n_v[1]</a></span>";
														echo  "</li>";
													} 
												}
												
												if ($n_n[2] > 0) 
												{
													if (($niv[2] <> $n_n[2]) or ($cla[2] <> $n_v[2]))
													{
														$cla[2] = $n_v[2];
														$niv[2] = $n_n[2];
												 
														if ($n_n[3] > 0) 
														{
															echo "<li id=\"$n_v[2]\" data-options=\"state:'closed'\">";
															echo "<span>$n_v[2]</span>";
															echo "<ul>";
														}  
														else 
														{
															echo "<li id=\"$niv[6]\">";
															echo "<span><a href=\"#\" style=\"text-decoration:none;color:black;\" ondblclick=\"getSelected()\">$n_v[2]</a></span>";
															echo  "</li>";
														} 			  											  
													}  
													if ($n_n[3] > 0) 
													{	
														if (($niv[3] <> $n_n[3]) or ($cla[3] <> $n_v[3]))
														{
															$cla[3] = $n_v[3];
															$niv[3] = $n_n[3];
									
															if ($n_n[4] > 0) 
															{
																echo "<li id=\"$n_v[3]\" data-options=\"state:'closed'\">";
																echo "<span>$n_v[3]</span>";
																echo "<ul>";
															}  
															else 
															{
																echo "<li id=\"$niv[6]\">";
																echo "<span><a href=\"#\" style=\"text-decoration:none;color:black;\" ondblclick=\"getSelected()\">$n_v[3]</a></span>";
																echo  "</li>";
															} 			  									
														}  
														if ($n_n[4] > 0) 
														{
															if (($niv[4] <> $n_n[4]) or ($cla[4] <> $n_v[4]))
															{
																$cla[4] = $n_v[4];
																$niv[4] = $n_n[4];
										
																if ($n_n[5] > 0) 
																{
																	echo "<li id=\"$n_v[4]\" data-options=\"state:'closed'\">";
																	echo "<span>$n_v[4]</span>";
																	echo "<ul>";
																}  
																else 
																{
																	echo "<li id=\"$niv[6]\">";
																	echo "<span><a href=\"#\" style=\"text-decoration:none;color:black;\" ondblclick=\"getSelected()\">$n_v[4]</a></span>";
																	echo  "</li>";
																}  
										 
															}  
															if ($n_n[5] > 0) 
															{   
																if (($niv[5] <> $n_n[5]) or ($cla[5] <> $n_v[5]))
																{
																	$cla[5] = $n_v[5];
																	$niv[5] = $n_n[5];
																	echo "<li id=\"$niv[6]\">";
																	echo "<span><a href=\"#\" style=\"text-decoration:none;color:black;\" ondblclick=\"getSelected()\">$n_v[5]</a></span>";
																	echo  "</li>";
																}  
															}
														} 	
													}  	
												}													
											}// FIN WHILE
								echo "</ul>";
							echo "</ul>";
						echo "</li>";
					echo "</ul>";
				}// fin del segundo else
			}// fin del primer else			

	}// fin db	
 }
 
function guardaTablas($divClass ,$titulo , $name, $class, $cv_principal , $id , $db , $value){
	$db = conectar();
	if($db){

		$sql_tabla = sql($name,$id , $value );
		$res_tabla = pg_query($db, $sql_tabla [1]);

		if (pg_num_rows ($res_tabla) == 0){
			$sql_origin = sql($name,$id , $value );
			$res_origin = pg_query($db, $sql_origin [2]);
			tabla($divClass ,$titulo , $name, $class, $cv_principal , $id , $db);
		}
		else{ tabla($divClass ,$titulo , $name, $class, $cv_principal , $id , $db);}
	}
 } 

function deleteAL($id , $cv_principal , $autores, $ligas ){
	$db = conectar();
	if($db){
		$sql_delete = sql($autores,$id , $cv_principal );
		$res_delete = pg_query($db, $sql_delete [3]);

		echo "1";
	}
 } 
 
?>