<?php
session_start();
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");

	
	connect();
	$idplanificacion =  $_POST["idplanificacion"];

	$idprovincia = $_SESSION["IDProvincia"];


	$q = "SELECT FechaProgramada, IDProvincia FROM planificaciones WHERE IDPlanificacion = " . $idplanificacion;
	$result = mysql_query($q);
	

	$reg = mysql_fetch_assoc($result);

	if($idprovincia == $reg["IDProvincia"]) {

		if ($result)  {

			if($reg["FechaProgramada"]) {
				echo $reg["FechaProgramada"];
			} else {
				echo 2;
			}

		
		
		} else {
			echo 2;
		}

		
		
	

		

		
		
	} else {
		echo "Juanquer";
	}

	desconnect();
 