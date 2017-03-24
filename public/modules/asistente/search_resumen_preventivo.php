<?php
session_start();
	require_once("../../../includes/database.inc.php");
	require_once("../../../includes/functions.inc.php");

	$months = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
	connect();
	$idplanificacion =  $_POST["idplanificacion"];

	$idprovincia = $_SESSION["IDProvincia"];

	$q = "SELECT * FROM planificaciones WHERE IDPlanificacion = " . $idplanificacion;
	$result = mysql_query($q);
	

	$reg = mysql_fetch_assoc($result);

	$fecha_planificacion = explode(' ', $reg["FechaPlanificacion"]);
	$min_fecha = $fecha_planificacion[0];
	$partes_fecha = explode('-', $min_fecha);
	$dias_mes_actual = date('t', strtotime($min_fecha));
	$max_fecha = $partes_fecha[0] . "-" . $partes_fecha[1] . "-" . $dias_mes_actual;



	if($idprovincia == $reg["IDProvincia"] && $reg["Tipo"] == 'preventivo') {


		$prioridad = $reg["Prioridad"];
		switch ($prioridad) {
			case '7':
			case '6':
			$observaciones = $reg["Observaciones"];
			
				$detalle = "<span class='detalle'><span class='icon3-warning'></span> <span class='observaciones'>" . $observaciones . "</span></span>";
				break;

			case '1':
			case '2':

				$fecha_limite = new DateTime($reg["FechaLimite"]);
				$mes = (int) $fecha->format('n');
				$mes = $months[--$mes];
				$detalle = "<span class='detalle'>Limite: " . $fecha_limite->format('j') . " " . $mes . "</span>";
				break;


			
			default:
				# code...
				break;
		}
		
		
		if(!$reg["FechaProgramada"]) {

			


			

		

			echo $detalle;

			echo "<div class='reg-item-tipo-validate-input-container fIU-list-item-container'><span class='icon3-calendar-plus-o icon-text reg-item-tipo-validate-input-icon'></span><label class='fIU-list-item-left-label reg-item-tipo-validate-input-label' >PLANIFICAR</label><input placeholder='aaaa-mm-dd' type='date' min='" . $min_fecha . "' max='" . $max_fecha ."' value='' class='fecha-plan fIU-list-item-input'></div>";
		} else {

			echo "<div class='reg-item-tipo-validate-input-container planificado fIU-list-item-container'><span class='planificado icon3-calendar-check-o icon-text reg-item-tipo-validate-input-icon'></span><label class='planificado fIU-list-item-left-label reg-item-tipo-validate-input-label' >PLANIFICADO</label><input placeholder='aaaa-mm-dd' type='date' value='" . $reg["FechaProgramada"] ."' min='" . $min_fecha . "'  max='" . $max_fecha ."' class='fecha-plan planificado fIU-list-item-input'></div>";

		}
	} else {
		echo "Qué intentas. :P";
	}

	desconnect();
 
?>