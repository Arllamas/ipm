<?php
	

	// Consulta de unidades 
	$qUnidades="select CONCAT(Unidad,' ',TipoUnidad) AS Unidad, IDUnidad, Tipo from unidades WHERE IDProvincia = " . $_SESSION["IDProvincia"] . " AND Tipo = 'unidad' order by Unidad"; 

		//Ejecutar consulta
		$resultUnidades = mysql_query($qUnidades);


	// Consulta de vehículos de reserva provincial
	$qReserva = "select CONCAT(Unidad,' ',TipoUnidad) AS Reserva, IDUnidad, Tipo from unidades WHERE IDProvincia = " . $_SESSION["IDProvincia"] . " AND Tipo = 'reserva'";
		// Ejecutar consulta
		$resultReserva = mysql_query($qReserva);

		// Manejador de los vehículos de reserva
		$rowR = mysql_fetch_assoc($resultReserva);
	
	// Consulta de elementos
	// Mas usados	
	$q_elementos_mas_usados = 'SELECT * FROM `elementos`where MasUsado = 1 order by Nombre ASC';
	
	// Todos
	$q_elementos_todos = 'SELECT * FROM `elementos` order by Nombre ASC';

		// Ejecutar consulta
		$result_elementos_mas_usados = mysql_query($q_elementos_mas_usados);
		$result_elementos_todos = mysql_query($q_elementos_todos);

		// Meter los elementos mas usados en un array para poder usarlos en distintos campos
		// Inicializar array elemento
		$elementos_mas = array(); 
		$contador_e_m = 0;
		while($row_elemento_mas = mysql_fetch_array($result_elementos_mas_usados)) { 
			
			$elementos_mas[$contador_e_m]['id'] = $row_elemento_mas['IDElemento']; 
			$elementos_mas[$contador_e_m]['nombre'] = $row_elemento_mas['Nombre']; 
			$contador_e_m++;
		}  

		// Meter todos los elementos en un array para poder usarlos en distintos campos
		// Inicializar array elemento
		$elementos_todos = array(); 
		$contador_e_t = 0;
		while($row_elemento_todos = mysql_fetch_array($result_elementos_todos)) { 
			
			$elementos_todos[$contador_e_t]['id'] = $row_elemento_todos['IDElemento']; 
			$elementos_todos[$contador_e_t]['nombre'] = $row_elemento_todos['Nombre']; 
			$contador_e_t++;
		}  

		// Mostramos en pantalla
		// pre($elementos_mas);
		// pre($elementos_todos);
	

	// Fecha y hora actual
	$currentDT = explode("+",date(c));
	$cdt = $currentDT[0];

	$min_dt = strtotime('-5 day' , strtotime($cdt));
	$min_dt = date("Y-m-d" . "\T". "H:i:s" , $min_dt);


	// Traer la vista
	require_once("public/modules/intervenciones/insert.tmp.php");


?>

