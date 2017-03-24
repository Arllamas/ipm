<?php

function get_fecha($fecha, $meses, $dias, $cuando) {

		$date = new DateTime($fecha);

		$anno = $date->format('Y');
		$mes = $date->format('m');
		$dia = $date->format('d');

	if($dias == 0 && $meses != 0) {

		for ($i=0; $i < $meses; $i++) { 

			$fecha_actual = $anno . "-" . $mes . "-" . $dia;



			$fecha_nueva = ($cuando == 'siguiente') ? get_mes_siguiente($fecha_actual) : get_mes_anterior($fecha_actual);
			

			$fecha_aux = explode("-", $fecha_nueva);
			$anno = $fecha_aux[0];
			$mes = $fecha_aux[1];
			$dia = $fecha_aux[2];
		}

	} else {

		if($meses != 0) {

			for ($i=0; $i < $meses; $i++) { 

				$fecha_actual = $anno . "-" . $mes . "-" . $dia;
				
				$fecha_nueva = ($cuando == 'siguiente') ? get_mes_siguiente($fecha_actual) : get_mes_anterior($fecha_actual);
			

				$fecha_aux = explode("-", $fecha_nueva);
				$anno = $fecha_aux[0];
				$mes = $fecha_aux[1];
				$dia = $fecha_aux[2];				
			
			}

		}
			

		if($dias < 0) {
			$fecha_actual = $anno . "-" . $mes . "-01";
			$dias_mes_actual = date('t', strtotime($fecha_actual));
			$dia = $dias_mes_actual;

		} else {
			for ($i=0; $i < $dias; $i++) { 

				$fecha_actual = $anno . "-" . $mes . "-" . $dia;

				$fecha_nueva = ($cuando == 'siguiente') ? get_dia_siguiente($fecha_actual) : get_dia_anterior($fecha_actual);
			
				$fecha_aux = explode("-", $fecha_nueva);
				$anno = $fecha_aux[0];
				$mes = $fecha_aux[1];
				$dia = $fecha_aux[2];


			}
		}

		
	}

	$fecha_final = new DateTime($anno . "-" . $mes . "-" . $dia);
	
	return $fecha_final->format('Y-m-d');	

}
function get_dia_siguiente($fecha) {

	$dias_mes_actual = date('t', strtotime($fecha));

	$fecha_aux = explode("-", $fecha);
	$anno = (int) $fecha_aux[0];
	$mes = (int) $fecha_aux[1];
	$dia = (int) $fecha_aux[2];


	if($dia == $dias_mes_actual) {
		if($mes == 12) {
			$anno++;
			$mes = 1;
			$dia = 1;
		} else {
			$mes++;
			$dia = 1;
		}
	} else {
		$dia++;
	}

	


	return $anno . "-" . $mes . "-" . $dia;
	
}
function get_mes_siguiente($fecha) {

	$fecha_aux = explode("-", $fecha);
	$anno = (int) $fecha_aux[0];
	$mes = (int) $fecha_aux[1];
	$dia = (int) $fecha_aux[2];

	if($mes == 12) {
		$anno++;
		$mes = 1;
		$dia = 1;
	} else {
		$mes++;
		$dia = 1;
	}

	
	return $anno . "-" . $mes . "-" . $dia;

}

function get_mes_anterior($fecha) {


	$dias_mes_actual = date('t', strtotime($fecha));


	$fecha_aux = explode("-", $fecha);
	$anno = (int) $fecha_aux[0];
	$mes = (int) $fecha_aux[1];
	$dia = (int) $fecha_aux[2];

	


	if($mes == 1) {
		$anno--;
		$mes = 12;
		$dia = $dias_mes_actual;
	} else {
		$mes--;
		$dia = $dias_mes_actual;
	}

		
	return $anno . "-" . $mes . "-" . $dia;

}

function get_dia_anterior($fecha) {

	$dias_mes_actual = date('t', strtotime($fecha));


	$fecha_aux = explode("-", $fecha);
	$anno = (int) $fecha_aux[0];
	$mes = (int) $fecha_aux[1];
	$dia = (int) $fecha_aux[2];


	if($dia == 1) {
		if($mes == 1) {
			$anno--;
			$mes = 12;
			$dia = $dias_mes_actual;
		} else {
			$mes--;
			$dia = $dias_mes_actual;
		}
	} else {
		$dia--;
	}


	return $anno . "-" . $mes . "-" . $dia;
	
}


function set_info_planificacion_excepcion($nueva_prioridad) {

	global $prioridad;
	global $observaciones;

		switch ($nueva_prioridad) {
			case 6:
				$prioridad = $nueva_prioridad;
				$observaciones = "No se han realizado preventivos a este vehículo";
				break;
		}
}


function set_info_planificacion($tipo, $dias, $dias_ultimo_preventivo, $diferencia,  $km) {

	$fecha_planificacion = date("Y") . "-" . date('m') . "-" . date('d');
	$fecha_limite_mitad = get_dia_habil(get_fecha($fecha_planificacion, 1, 14, "siguiente"));
	$fecha_limite_final = get_dia_habil(get_fecha($fecha_planificacion, 1, -1, "siguiente"));


	
	global $prioridad;
	global $observaciones;
	global $fecha_limite;
	

	switch ($tipo) {
		case 'preventivo':
			if(($dias-60) > $dias_ultimo_preventivo){
				if ($diferencia >= 0) {
					if($km < $diferencia) {
						$prioridad = 1;
						$fecha_limite = $fecha_limite_mitad;
					} elseif (($km-200) < $diferencia) {
						$prioridad = 2;
						$fecha_limite = $fecha_limite_final;
					} else {
						 $prioridad = 12;
					}

				} else {
					$prioridad = 7;
					$observaciones = "Incoherencia en el kilometraje";
				}	

			} elseif($dias > $dias_ultimo_preventivo) {
				$prioridad = 2;
				$fecha_limite = $fecha_limite_final;

			} else {
				$prioridad = 1;
				$fecha_limite = $fecha_limite_mitad;
			}
			break;
	}


}



function get_dia_habil($fecha) {

	if(date('w', strtotime($fecha)) !=0 && date('w', strtotime($fecha)) != 6) {

		if(!es_festivo($fecha)){

			return $fecha;	

		} else {	

			return get_dia_habil(get_dia_anterior($fecha));
				
		}
		
	} else {
		return get_dia_habil(get_dia_anterior($fecha));
	}
}

function es_festivo($fecha) {

	$q = "select * from festivos where Fecha = '" . $fecha . "'";
	$r = mysql_query($q);

	return (mysql_num_rows($r) != 0)? true : false;
}

function get_ultima_intervencion($idmatricula) {

	$q = "SELECT IDIntervencion, FechaIntervencion, Kilometros FROM intervenciones WHERE IDMatricula = " . $idmatricula . " ";
	$q .= "AND FechaIntervencion = (SELECT max(FechaIntervencion) AS FechaIntervencion
								FROM intervenciones WHERE IDMatricula = " . $idmatricula . ")";
	$r = mysql_query($q);
	
	$ultima_intervencion = mysql_fetch_assoc($r);

	$hay_intervenciones = ($ultima_intervencion["IDIntervencion"]) ? true: false;

	return ($hay_intervenciones) ? $ultima_intervencion : false;
}

function get_ultimo_preventivo($idmatricula) {

	$q = "SELECT IDIntervencion, FechaIntervencion, Kilometros, MPreventivo FROM intervenciones WHERE IDMatricula = " . $idmatricula . " AND MPreventivo = 1 ";
	$q .= "AND FechaIntervencion = (SELECT max(FechaIntervencion) AS FechaIntervencion
								FROM intervenciones WHERE IDMatricula = " . $idmatricula . " AND MPreventivo = 1)";

	$r = mysql_query($q);
	
	$ultimo_preventivo = mysql_fetch_assoc($r);

	$hay_preventivo = ($ultimo_preventivo["IDIntervencion"]) ? true: false;

	return ($hay_preventivo) ? $ultimo_preventivo : false;
}

function get_dias_ultimo_preventivo($id_matricula) {

	$q_ult_intervencion = "SELECT MAX(FechaIntervencion) AS FechaIntervencion FROM intervenciones WHERE IDMatricula = " . $id_matricula . " AND MPreventivo = 1";
	$result = mysql_query($q_ult_intervencion);
	$reg = mysql_fetch_assoc($result);
	

	$fecha_intervencion = $reg["FechaIntervencion"];
	$fecha_actual = date("Y") . "-" . date('m') . "-" . date('d') . " " . date('H') . ":" . date('i') . ":" . date('s');

	$fecha_intervencion = new DateTime($fecha_intervencion);
	$fecha_actual = new DateTime($fecha_actual);
	$interval = $fecha_intervencion->diff($fecha_actual);

	$dias = abs($interval->format('%R%a'));

	return $dias;
}

function esta_en_garantia($id_matricula) {

	$q = "SELECT FechaMatriculacion, IDVehiculo from matriculas WHERE IDMatricula = " . $id_matricula;
	$r = mysql_query($q);
	$reg = mysql_fetch_assoc($r);

	$fecha_matriculacion = $reg["FechaMatriculacion"];
	$fecha_actual = date("Y") . "-" . date('m') . "-" . date('d');

	$fecha_matriculacion = new DateTime($fecha_matriculacion);
	$fecha_actual = new DateTime($fecha_actual);
	$interval = $fecha_matriculacion->diff($fecha_actual);

	$annos_desde_fecha_matriculacion = abs($interval->format('%R%y'));

	$q_garantia_fabricante = "SELECT Garantia from vehiculos WHERE IDVehiculo = " . $reg["IDVehiculo"];
	$r_garantia = mysql_query($q_garantia_fabricante);

	$reg_garantia = mysql_fetch_assoc($r_garantia);
	
	$annos_garantia_vehiculo = $reg_garantia["Garantia"];
	
	return ($annos_garantia_vehiculo > $annos_desde_fecha_matriculacion) ? true : false;
}

?>
