<?php
	$nombreMantenedora= trim($_SESSION["Nombre"]);
	$provinciasMantenedora = array();
	
	foreach ($_SESSION["IDProvincia"] as $key => $IDProvincia) {
		$q = "SELECT * from provincias where IDProvincia = "  . $IDProvincia;
		
		$resultado = mysql_query($q);
		$row = mysql_fetch_assoc($resultado);
		$provinciasMantenedora[$key]['IDProvincia'] = $row["IDProvincia"];
		$provinciasMantenedora[$key]['Nombre'] = $row["Nombre"];
	}
	
$directorio_mantenedora = clean_name($nombreMantenedora);
$dir_province =  clean_name(get_name_province($provinciasMantenedora[0]['IDProvincia']));
$ruta = 'almacen/' . $directorio_mantenedora . "/" . $dir_province . "/";
$patron_glob = $ruta  . "*.xls";


	// echo mb_strtoupper(get_name_province($_SESSION["IDProvincia"]));
	require_once("public/modules/informes/list.man.tmp.php");


?>