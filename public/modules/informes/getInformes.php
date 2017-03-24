<?php
session_start();

if ($_SESSION["IDUsuario"] == null) {
  Header("Location: login.php");
}

if ($_POST) {
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");

	connect();

	
	$idprovincia = $_POST['valor'];


    $nombreMantenedora= trim($_SESSION["Nombre"]);
	$directorio_mantenedora = clean_name($nombreMantenedora);
	$dir_province =  clean_name(get_name_province($idprovincia));
	$ruta = '../../../almacen/' . $directorio_mantenedora . "/" . $dir_province . "/";
	$patron_glob = $ruta  . "*.xls";

	desconnect();

	require_once ("getInformes.tmp.php");
} else {
	Header("Location: ../../../logout.php");
}

?>