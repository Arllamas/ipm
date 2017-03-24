<?php
	$IDProvincia = $_SESSION["IDProvincia"];
	$fecha_aux = new DateTime(date('Y') . "-" . date('m') .  "-" . date('d'));
	$months = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIENDO");
	$anno = $fecha_aux->format('Y');
	$mes = $fecha_aux->format('m');
	$dia = '01';

	$q = "SELECT P.*, M.Matricula, U.IDUnidad, U.Unidad, U.TipoUnidad FROM planificaciones P 
			LEFT JOIN matriculas M on M.IDMatricula = P.IDMatricula 
			LEFT JOIN unidades U on P.IDUnidad = U.IDUnidad 
		  WHERE FechaPlanificacion = '" . $anno . "-" . $mes . "-" . $dia . "' AND P.IDProvincia = " . $IDProvincia ." ORDER BY U.IDUnidad, IDMatricula, Tipo";


	$result = mysql_query($q);

	$qU = "SELECT * from unidades where IDProvincia =" . $IDProvincia;

	$resultU = mysql_query($qU);


	$contador = 0;

	while ($planificaciones = mysql_fetch_assoc($result)) {
		

		if($matricula_anterior == $planificaciones["IDMatricula"]) {

				$total_tipos = count($planificaciones_preparada[$contador-1]["Tipo"]);

				$planificaciones_preparada[$contador-1]["Tipo"][$total_tipos]["IDPlanificacion"] = $planificaciones["IDPlanificacion"];
				$planificaciones_preparada[$contador-1]["Tipo"][$total_tipos]["Tipo"] = $planificaciones["Tipo"];
				$planificaciones_preparada[$contador-1]["Tipo"][$total_tipos]["FechaLimite"] = $planificaciones["FechaLimite"];
				$planificaciones_preparada[$contador-1]["Tipo"][$total_tipos]["FechaProgramada"] = $planificaciones["FechaProgramada"];
				$planificaciones_preparada[$contador-1]["Tipo"][$total_tipos]["FechaRealizada"] = $planificaciones["FechaRealizada"];
				

		} else {
				$planificaciones_preparada[$contador]["IDProvincia"] = $planificaciones["IDProvincia"];
				$planificaciones_preparada[$contador]["IDMatricula"] = $planificaciones["IDMatricula"];
				$planificaciones_preparada[$contador]["IDUnidad"] = $planificaciones["IDUnidad"];
				$planificaciones_preparada[$contador]["Matricula"] = $planificaciones["Matricula"];
				$planificaciones_preparada[$contador]["Unidad"] = $planificaciones["Unidad"];
				$planificaciones_preparada[$contador]["TipoUnidad"] = $planificaciones["TipoUnidad"];
				$planificaciones_preparada[$contador]["FechaPlanificacion"] = $planificaciones["FechaPlanificacion"];


				$planificaciones_preparada[$contador]["Tipo"][0]["IDPlanificacion"] = $planificaciones["IDPlanificacion"];
				$planificaciones_preparada[$contador]["Tipo"][0]["Tipo"] = $planificaciones["Tipo"];
				$planificaciones_preparada[$contador]["Tipo"][0]["FechaLimite"] = $planificaciones["FechaLimite"];
				$planificaciones_preparada[$contador]["Tipo"][0]["FechaProgramada"] = $planificaciones["FechaProgramada"];
				$planificaciones_preparada[$contador]["Tipo"][0]["FechaRealizada"] = $planificaciones["FechaRealizada"];
	

				$contador++;

		}

		
		$matricula_anterior = $planificaciones["IDMatricula"];
	}

	

	require_once("public/modules/asistente/list.tmp.php")

?>
