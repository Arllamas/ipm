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
	
	$qAccion = "SELECT IDAccionElemento, Accion FROM `acciones_elemento` "; 
	$qAccion .= "WHERE IDElemento = " . $IDElemento;

	$resultado_acciones = mysql_query($qAccion);

	if ($resultado_acciones) {
		$has = mysql_num_rows($resultado_acciones);	
	}

	desconnect();

	require_once ("getAccion.tmp.php");
} else {
	Header("Location: ../../../logout.php");
}

?>
