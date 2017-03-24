<?php
session_start();

if ($_SESSION["IDUsuario"] == null) {
  Header("Location: ../../../login.php");
}

if ($_POST) {
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");
	connect();
	$IDUnidad = abs(intval($_POST["valor"]));

	if ($_SESSION["IDProvincia"] != get_provincia_por_unidad($IDUnidad)) {
	  Header("Location: ../../../logout.php");
	}
	
	$qMatriculas = "select Matricula, IDMatricula, Tipo from matriculas where";	
	$qMatriculas .= " IDUnidad = " . $IDUnidad . " AND Estado = 'Alta'";	 
	$qMatriculas .= " order by Matricula ASC";

	$resultMatriculas = mysql_query($qMatriculas);

	if ($resultMatriculas) {
		$has = mysql_num_rows($resultMatriculas);	
	}

	desconnect();

	require_once ("getMatriculas.tmp.php");
} else {
	Header("Location: ../../../logout.php");
}

?>



	