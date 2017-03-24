<?php
session_start();
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");

	
	connect();
	$idplanificacion =  $_POST["idplanificacion"];

	$idprovincia = $_SESSION["IDProvincia"];


	$q = "SELECT * FROM planificaciones WHERE IDPlanificacion = " . $idplanificacion;
	$result = mysql_query($q);
	

	$reg = mysql_fetch_assoc($result);
	$fecha_programada = new DateTime($_POST["FechaProgramada"]);
	$fecha_planificacion = new DateTime($reg["FechaPlanificacion"]);

	if($fecha_programada->format('Y') == $fecha_planificacion->format('Y') && $fecha_programada->format('m') == $fecha_planificacion->format('m') )

		if($idprovincia == $reg["IDProvincia"]) {
			
			$q = "UPDATE planificaciones SET FechaProgramada = '" . $fecha_programada->format('Y-m-d') . "' WHERE IDPlanificacion = " . $idplanificacion;
				
				$result = mysql_query($q);

			

			if ($result)  {

			echo $_POST["FechaProgramada"];
			
			} else {
				echo 2;
			}
			
		} else {
			echo "Juanquer";
		}
	else {
		echo 2;
	}

	desconnect();
 
?>