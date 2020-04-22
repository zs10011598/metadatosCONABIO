<?php
$metadato=$_GET["metadato"];
$metadato = trim($metadato);
$fileMetadato=$_GET["fileMetadato"];
$uploaddir = 'ficheros/';
$uploadfile = $uploaddir . $fileMetadato;

$contenido=$_GET["contenido"];


$jsondata = array();
$jsondata["contenido"] = trim($contenido);
//echo $metadato."<br>";
$i=$metadato + 15;

$file = fopen($uploadfile, "r");


$num_lineas = 1; 
switch ($contenido)
{
	case "vectores":
	{
		while(!feof($file)) {
		 $linea = fgets($file);
		   if( ++$num_lineas > $metadato and ++$num_lineas <= ($metadato + 17))
			{
				$layerName = substr($linea, 0, 10);
				$geometryName = substr($linea, 0, 8);
				$featureCount = substr($linea, 0, 13);
				$extent = substr($linea, 0, 6);
				
				$geogcss =substr((trim($linea)), 0, 6);
				$datum = substr((trim($linea)), 0, 5);
				$proyec = substr((trim($linea)), 0, 6);
				
				
				if ($layerName == "Layer name")
				{
					$nameLayer = substr($linea,12);
					$jsondata["nameLayer"] = trim($nameLayer);
				}
				if ($geometryName == "Geometry")
				{
					$geometry = substr($linea,10);
					$jsondata["geometry"] = trim($geometry);
				}
				if ($featureCount == "Feature Count")
				{
					$count = substr($linea,15);
					$jsondata["count"] = trim($count);
				}
				if ($extent == "Extent")
				{
					$extCordinate = substr($linea,9);
					$coordenadas = preg_split("/[\s,]+()/", $extCordinate);
					
					$jsondata["Cordinate"] = array();					
					$jsondata["Xmin"] = trim($coordenadas[0]);
					$jsondata ["Ymin"] = trim(substr($coordenadas[1],0,-1));
					$jsondata ["Xmax"] = trim(substr($coordenadas[3],1,-1));
					$jsondata ["Ymax"] = trim(substr($coordenadas[4],0,-1));
				}
				if ($geogcss == "GEOGCS")
				{
					$geogcssName = substr((trim($linea)),8,-2);
					$jsondata["geogcssName"] = trim($geogcssName);
				}
				if ($datum == "DATUM")
				{
					$datumName = substr((trim($linea)),7,-2);
					$jsondata["datumName"] = trim($datumName);
				}
				
				if ($proyec == "PROJCS")
				{
					$proyeccion = substr((trim($linea)),8,-2);
					$jsondata["proyeccion"] = trim($proyeccion);
				}
			}
			
		} // fin while
	} // fin case "vectores"
	case "tif":
	{
		
		$jsondata["Cordinate"] = array();
		while(!feof($file)) {
			$linea = fgets($file);
			if( ++$num_lineas > $metadato and ++$num_lineas <= ($metadato + 25))
			{
				$tipoDato = substr($linea, 0, 5);
				$sizeIs = substr($linea, 0,4);
				$pixelSize = substr($linea, 0,5);
				$Origin = substr($linea, 0,6);
				$extentMin = substr($linea, 0,10);
				$extentMax = substr($linea, 0,11);
				
				$jsondata["tifDato"] = "Pixel";
				
				if ($sizeIs == "Size")
				{
					$colunas_renglones = substr($linea,8);
					$split_columnas  = explode(",", $colunas_renglones);
					$jsondata["tifColunmas"] = trim($split_columnas [0]);
					$jsondata["tifRenglones"] =  trim($split_columnas [1]);
					$jsondata["count"] =  (($split_columnas [0])*($split_columnas [1])) ;
				}
				if ($pixelSize == "Pixel")
				{
					$pixelSizeCoor = substr($linea,14);
					$split_pixelSize  = explode(",", $pixelSizeCoor);
					$jsondata["tifPixelY"] =  trim($split_pixelSize [0]);
					$jsondata["tifPixelX"] =  substr(trim($split_pixelSize [1]),0 ,-1);
					
				}
				if ($Origin == "Origin")
				{
					$CoorOrigin = substr($linea,10);
					$split_Origin  = explode(",", $CoorOrigin);
					$jsondata["tifRasterX"] =  trim($split_Origin [0]);
					$jsondata["tifRasterY"] =  substr(trim($split_Origin [1]),0 ,-1);
					
				}
				if ($extentMin == "Lower Left")
				{
					
					$extCordinateTif = substr($linea,13);
					$coordenadasMin  = explode(",", $extCordinateTif);
					$jsondata["Xmin"] = trim($coordenadasMin[0]);
					$jsondata ["Ymin"] = trim(substr($coordenadasMin[1],0,-4));
				}
				if ($extentMax == "Upper Right")
				{
					
					$extCordinateMax = substr($linea,13);
					$coordenadasMax  = explode(",", $extCordinateMax);
					$jsondata["Xmax"] = trim($coordenadasMax[0]);
					$jsondata["Ymax"] = trim(substr($coordenadasMax[1],0,-4));
				}
			}
		} // fin while
	} // fin case "tif":
	default:
}



echo json_encode($jsondata);
//	$layerName = trim(fgets($file));
//	$extraction = substr($layerName, 0, 10);
/*	if ($extraction == "Layer name")
	{
		$extrac = substr($layerName, (12 + ((strlen($layerName)) * (-1))));
		if($extrac == $metadato)
		{
			$i = 1;
			
		}
	}*/
	
//}
	//$jsondata["success"] = true;
//	$jsondata["data"]["message"] = 'Hola! El valor recibido es correcto. '.$i;
//
//	fclose($file);
	
	

//while ($galletas < 10){

//$galletas++;
//}

//header('Content-type: application/json; charset=utf-8');
    //echo json_encode($jsondata);
    //exit();
//$jsondata = array();
//
//if( isset($_GET['parametro1']) ) {
//
//    if( $_GET['parametro1'] == 'valor1' ) {
//
//        $jsondata['success'] = true;
//        $jsondata['message'] = 'Hola! El valor recibido es correcto.';
//
//    } else {
//
//        $jsondata['success'] = false;
//        $jsondata['message'] = 'Hola! El valor recibido no es correcto.';
//
//    }
//
//    //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
//    header('Content-type: application/json; charset=utf-8');
//    echo json_encode($jsondata);
//    exit();
//
//}




?>
