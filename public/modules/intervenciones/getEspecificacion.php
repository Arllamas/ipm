<?php
session_start();

if ($_SESSION["IDUsuario"] == null) {
  Header("Location: ../../../login.php");
}

if ($_POST) {
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");
	connect();
	$IDElemento = abs(intval($_POST["valor"]));
	
	$qEspecificacion = "SELECT IDEspecificacionElemento, Especificacion FROM `especificaciones_elemento` "; 
	$qEspecificacion .= "WHERE IDElemento = " . $IDElemento;

	$resultado_especificacion = mysql_query($qEspecificacion);

	if ($resultado_especificacion) {
		$has = mysql_num_rows($resultado_especificacion);	
	}

	desconnect();

	require_once ("getEspecificacion.tmp.php");
} else {
	Header("Location: ../../../logout.php");
}

?>
