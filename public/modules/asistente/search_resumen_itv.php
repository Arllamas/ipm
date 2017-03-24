<?php
session_start();

	$months = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");

	connect();
	$idplanificacion =  $_POST["idplanificacion"];

	$idprovincia = $_SESSION["IDProvincia"];

	$q = "SELECT * FROM planificaciones WHERE IDPlanificacion = " . $idplanificacion;
	$result = mysql_query($q);
	

	$reg = mysql_fetch_assoc($result);



	if($idprovincia == $reg["IDProvincia"] && $reg["Tipo"] == 'itv') {

		$q_fecha_cita = "select FechaCitaITV from matriculas where IDMatricula = " . $reg["IDMatricula"];

		$r_fecha_cita = mysql_query($q_fecha_cita);
		$reg_fecha_cita = mysql_fetch_assoc($r_fecha_cita);


		
		if(!$reg_fecha_cita["FechaCitaITV"]) {

			


			$fecha = new DateTime($reg["FechaPlanificacion"]);

			$mes = (int) $fecha->format('n');
			$mes = $months[--$mes];
			
			echo "<span class='detalle-fecha-limite'>Limite: " . $fecha->format('j') . " " . $mes . "</span>";

			echo "<div class='reg-item-tipo-validate-input-container fIU-list-item-container'><span class='icon3-calendar-plus-o icon-text reg-item-tipo-validate-input-icon'></span><label class='fIU-list-item-left-label reg-item-tipo-validate-input-label' >CITAR ITV</label><input placeholder='aaaa-mm-dd'  type='datetime-local' value='' class='date-time fecha-plan fIU-list-item-input' min='". str_replace(" ", 'T', $reg["FechaPlanificacion"]) . "' max='". $reg["FechaPlanificacion"] . "''></div>";
		} else {

			echo "<div class='reg-item-tipo-validate-input-container planificado fIU-list-item-container'><span class='planificado icon3-calendar-check-o icon-text reg-item-tipo-validate-input-icon'></span><label class='planificado fIU-list-item-left-label reg-item-tipo-validate-input-label' >CITADO</label><input placeholder='aaaa-mm-dd' type='date'  min='". $FechaPlanificacion . "' value='" . $reg_fecha_cita["FechaCitaITV"] ."'  class='fecha-plan planificado fIU-list-item-input'></div>";

		}
	} else {
		echo "Qué intentas. :P";
	}

	desconnect();
 
?>