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
	
	$qRepuestos = "SELECT R.Nombre as Nombre, R.IDRepuesto as IDRepuesto FROM repuestos_elemento RE, repuestos R WHERE R.IDRepuesto = RE.IDRepuesto AND RE.IDElemento = " . $IDElemento;

	$resultado_repuestos = mysql_query($qRepuestos);

	if ($resultado_repuestos) {
		$has = mysql_num_rows($resultado_repuestos);	
	}

	desconnect();

	require_once ("getRepuestos.tmp.php");
} else {
	Header("Location: ../../../logout.php");
}

?>
