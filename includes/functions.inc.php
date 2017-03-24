<?php

	function salto($numero_saltos) {

		$salto = "";


		for ($i=0; $i < $numero_saltos; $i++) { 
			$salto .= "<br>";
		}


		echo $salto;
	}


	function is_today($datetime) {

		$dataParts = explode(" ", $datetime);

		$annoActual = date("Y");
		$mesActual = date("m");
		$diaActual = date("d");
		$horaActual = date("H");
		$minutoActual = date("i");
		$segundoActual = date("s");

		$fechaActual = $annoActual . '-' . $mesActual . '-' . $diaActual . ' ' . $horaActual . ":" . $minutoActual . ":" . $segundoActual;

		$dateAviso = $dataParts[0];
		$timeAviso = $dataParts[1];

		$diaAviso = substr($dateAviso, -2);
		$mesAviso = substr($dateAviso, 5, -3);
		$annoAviso = substr($dateAviso, 0, -6);

		$horaAviso = substr($timeAviso, 0, -6);
		$minutoAviso = substr($timeAviso, 3, -3);
		$segundoAviso = substr($timeAviso, 6);

		$fechaAviso = $annoAviso . '-' . $mesAviso . '-' . $diaAviso . ' ' . $horaAviso . ":" . $minutoAviso . ":" . $segundoAviso;

		
	
		

		$datetime1 = new DateTime($fechaAviso);
		$datetime2 = new DateTime($fechaActual);
		$interval = $datetime1->diff($datetime2);

		$dias = abs($interval->format('%R%a'));

		

		// if($dias == 0) {

		// 	echo $horaAviso;
	
		// 	echo $horaActual;
	
		// 	echo $minutoAviso;
	
		// 	echo $minutoActual;
	
		// 	echo $segundoAviso;
	
		// 	echo $segundoActual;

		// }


		// return ($days_between > 1 && $fecha->d != 0)? $days_between : "h";
		
		// printf('%d años, %d meses, %d días, %d horas, %d minutos', $fecha->y, $fecha->m, $fecha->d, $fecha->h, $fecha->i);


	}
function get_info_usuario() {   
	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
  	$os=array("WIN","MAC","LINUX");
  	 # definimos unos valores por defecto para el navegador y el sistema operativo
  	$info['Navegador'] = "OTHER";
  	$info['OS'] = "OTHER";

	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent) {
		$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		
		if ($s) {
			$info['Navegador'] = ucwords(strtolower($parent)) . " v(" . $version . ")";
		}
	}

	# OBTENEMOS EL SISTEMA OPERATIVO DEL USUARIO
	foreach($os as $val) {
		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
		$info['OS'] = $val;
	}


	// OBTENEMOS LA IP DEL USUARIO 
  	if ($_SERVER) {  
	  if ( $_SERVER["HTTP_X_FORWARDED_FOR"] ) {  
	       $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
	   } elseif ( $_SERVER["HTTP_CLIENT_IP"] ) {  
	       $ip = $_SERVER["HTTP_CLIENT_IP"];  
	   } else {  
	       $ip = $_SERVER["REMOTE_ADDR"];  
	   }  
	} else {  
	    if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {  
	       $ip = getenv( 'HTTP_X_FORWARDED_FOR' );  
	    } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {  
	       $ip = getenv( 'HTTP_CLIENT_IP' );  
	    } else {  
	       $ip = getenv( 'REMOTE_ADDR' );  
	    }  
	}
    
  
	  // OBTENEMOS EL PUERTO DEL USUARIO
	  $puerto = $_SERVER['REMOTE_PORT'];


	  require_once ('classes/MobileDetect/Mobile_Detect.php');
	  $detect = new Mobile_Detect;

	  // OBTENEMOS EL TIPO DE DISPOSITIVO DEL USUARIO [MOVILES o TABLET]
	  $checkM = $detect->isMobile();

      if($checkM) {
        $tipoDispositivo = "Smartphone";
      }

      $checkT = $detect->isTablet();

      if($checkT) {
        $tipoDispositivo = "Tablet";
      }

      if(!$checkT && !$checkM) {
        $tipoDispositivo = "Ordenador";
      } 


      // OBTENEMOS DETALLE DEL DISPOSITIVO DEL USUARIO [MOVILES o TABLET]
      $detalleDispositivo = "";
      foreach($detect->getRules() as $name => $regex) {
       $check = $detect->{'is'.$name}();
       if($check) {
        $detalleDispositivo .= $name . " | ";
          
        }
      }

      // OBTENEMOS VERSIONES DEL DISPOSITIVO DEL USUARIO [MOVILES o TABLET]
       foreach($detect->getProperties() as $name => $match) {
       $check = $detect->version($name);
       if($check!==false) {
        $versionesDispositivo .= $name . "(". $check .") | ";
          
        }
      }

      
    //Asignamos un valor a cada variable dentro del array
    return array(
    'IP' => $ip,
    'Puerto'  => $puerto,
    'Navegador' => $info['Navegador'],
    'OS' => $info['OS'],
    'Dispositivo' => $tipoDispositivo,
    'Detalle' => trim($detalleDispositivo),
    'Versiones' => trim($versionesDispositivo)
    );
} 


function grabar_session($info, $idusuario) {

$currentDT = explode("+",date(c));
$currentDT = str_replace('T', ' ', $currentDT[0]);

	$q = "INSERT INTO `control_sesiones` (`IDControlSesion`, `IDUsuario`, `Entrada`, `Salida`, `IP`, `Puerto`, `Navegador`, `OS`, `Dispositivo`, `Detalle`, `Versiones`) VALUES (NULL, ";
	$q .= $idusuario . ", ";
	$q .= "'" . $currentDT . "', ";
	$q .= "NULL, ";

	if($info['IP'] != ""){
		$q .= "'" . $info['IP'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['Puerto'] != ""){
		$q .= "'" . $info['Puerto'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['Navegador'] != ""){
		$q .= "'" . $info['Navegador'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['OS'] != ""){
		$q .= "'" . $info['OS'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['Dispositivo'] != ""){
		$q .= "'" . $info['Dispositivo'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['Detalle'] != ""){
		$q .= "'" . $info['Detalle'] . "', ";
	} else {
		$q .= "NULL, ";
	}

	if($info['Versiones'] != ""){
		$q .= "'" . $info['Versiones'] . "');";
	} else {
		$q .= "NULL);";
	}

	$result = mysql_query($q);

	// Devolvemos la id de la sesion generada
	return mysql_insert_id();

}

function cerrar_session($idsesion) {
	$currentDT = explode("+",date(c));
	$currentDT = str_replace('T', ' ', $currentDT[0]);

	$q = "UPDATE `control_sesiones` SET `Salida` = ";
	$q .= "'" . $currentDT . "' ";
	$q .= "WHERE `IDControlSesion` =  " . $idsesion;

	$result = mysql_query($q);

	return $result;
	
}


      

function procesarBoleano($booleanData) {
	return $booleanData ? "X": "";
}
function procesarFecha($date) {
	$date = substr($date,0,-3);
	$t_year  = substr($date,0,4);
	$t_month = substr($date,5,2);
	$t_day   = substr($date,8,2);
	$t_hours   = substr($date,11,2);
	$t_mins   = substr($date,14,2);


    return $t_day . "-" . $t_month . "-" . $t_year . " " . $t_hours . ":" . $t_mins;
}

function hayIntervenciones($idprovincia, $mes, $anno) {

	$q = "SELECT ";
	$q .= "count(i.FechaIntervencion) AS Total ";
	$q .= "FROM unidades u, intervenciones i ";
	$q .= "LEFT JOIN unidades ud ";
	$q .= "ON i.IDUnidadDestino = ud.IDUnidad ";
	$q .= "INNER JOIN matriculas m ";
	$q .= "ON i.IDMatricula = m.IDMatricula ";
	$q .= "WHERE i.IDUnidad = u.IDUnidad AND u.IDProvincia = " . $idprovincia . " ";
	$q .= "AND MONTH(i.FechaIntervencion) = " . $mes . " ";
	$q .= "AND YEAR(i.FechaIntervencion) = " . $anno . " ";
	$q .= "ORDER BY i.FechaIntervencion ASC";

	$resultado = mysql_query($q);
	$row = mysql_fetch_assoc($resultado);
	
	return ($row["Total"] > 0) ? true : false;
	
}

function procesarMotivo($valor) {

	switch ($valor) {
		case 'avisoAveria':
			return utf8_encode("AVISO DE AVERÍA");
			break;
		case 'desplazamiento':
			return "DESPLAZAMIENTO";
			break;
		case 'avisoUnidad':
			return "AVISO EN UNIDAD";
			break;
		case 'preventivos':
			return "ACT. PREVENTIVAS";
			break;
		case 'asistencia':
			return "ASISTENCIA";
			break;
	}
}

function get_provincia_por_unidad($idunidad) {
	$qProvincia = "select IDProvincia from unidades where IDUnidad = " . $idunidad;

	$resultP = mysql_query($qProvincia);
	$regP = mysql_fetch_assoc($resultP);

	return $regP["IDProvincia"];
}

function obtenerProvincia($id){
	$qProvincia = "select UPPER(Nombre) as Nombre from provincias where IDProvincia = " . $id;
	$resultP = mysql_query($qProvincia);
	$regP = mysql_fetch_assoc($resultP);

	return $regP["Nombre"];
}


function pre($a) {
		echo "<div style='border: 1px solid #000; background-color: #fff; padding: 10px; font: normal normal 12px Arial, Verdana;'>";
		echo "<pre>";
		print_r($a);
		echo "</pre>";
		echo "</div>";
	}

function get_name_province($id) {
		$q = "select Nombre from provincias where IDProvincia = ". $id;
		$result = mysql_query($q);
		$row = mysql_fetch_assoc($result);

		return $row["Nombre"];
	}
function get_info_report($file) {
		$meses = array('enero','febrero','marzo','abril','mayo','junio','julio', 'agosto','septiembre','octubre','noviembre','diciembre');

		$partesRuta = explode("/", $file);
		$partesArchivo = explode("-", str_replace(".xls", "", end($partesRuta)));

		$datos = array();

		$datos["Anno"] = substr($partesArchivo[0],0, 4); 
		$datos["Mes"] = substr($partesArchivo[0],4, 2); 
		$datos["Dia"] = substr($partesArchivo[0],6, 2);
		$datos["Hora"] = substr($partesArchivo[0],8, 2); 
		$datos["Minutos"] = substr($partesArchivo[0],10, 2); 
		$datos["Estado"] = end($partesArchivo);

		$datos["NombreMes"] = ucwords($meses[$datos["Mes"]-1]); 
		return $datos;
}
function get_nombre_consonate($name) {
		$name = utf8_decode($name);

		$name = trim($name);
		$name = strtolower($name);
		$name = str_replace(" ", "", $name);
		$name = str_replace("a", "", $name);
		$name = str_replace("e", "", $name);
		$name = str_replace("i", "", $name);
		$name = str_replace("o", "", $name);
		$name = str_replace("u", "", $name);
		$name = str_replace("á", "", $name);
		$name = str_replace("é", "", $name);
		$name = str_replace("í", "", $name);
		$name = str_replace("ó", "", $name);
	
				
		return strtoupper($name);
}
	function active_section($module) {
		$parts = explode("-", $module);
		$section = $parts[1];

		return ($module == "") ? "obras" : $section ;	
	}

	function cleanImageName($name) {
		$name = strtolower($name);
		$name = str_replace(" ", "-", $name);
		$name = time() . "-" . $name;
		return $name;
	}
	
	function clean_name($name) {
		$name = utf8_decode($name);

		$name = trim($name);
		$name = strtolower($name);
		$name = str_replace(" ", "-", $name);
		$name = str_replace("ñ", "n", $name);
		$name = str_replace("ç", "c", $name);
		$name = str_replace("á", "a", $name);
		$name = str_replace("é", "e", $name);
		$name = str_replace("í", "i", $name);
		$name = str_replace("ó", "o", $name);
		$name = str_replace("ú", "u", $name);
		$name = str_replace("à", "a", $name);
		$name = str_replace("à", "e", $name);
		$name = str_replace("à", "i", $name);
		$name = str_replace("à", "o", $name);
		$name = str_replace("à", "u", $name);
		$name = str_replace("ä", "a", $name);
		$name = str_replace("ä", "e", $name);
		$name = str_replace("ï", "i", $name);
		$name = str_replace("ö", "o", $name);
		$name = str_replace("ü", "u", $name);
		
		// $name = time() . "-" . $name;
		
		return $name;
	}

	function get_repuestos_separados($repuestos) {
		$repuestos = utf8_decode($repuestos);

		$repuestos = trim($repuestos);

		$repuestos = strtolower($repuestos);
		$repuestos = str_replace(";", ",", $repuestos);
		$repuestos = str_replace(".", ",", $repuestos);
		$repuestos = str_replace("_", ",", $repuestos);
		$repuestos = str_replace("-", ",", $repuestos);
		$repuestos = str_replace("'", ",", $repuestos);
		$repuestos = str_replace("`", ",", $repuestos);
		$repuestos = str_replace("+", ",", $repuestos);
		$repuestos = str_replace("*", ",", $repuestos);
		$repuestos = str_replace(":", ",", $repuestos);
		$repuestos = str_replace(">", ",", $repuestos);
		$repuestos = str_replace("<", ",", $repuestos);
		$repuestos = str_replace("|", ",", $repuestos);
		$repuestos = str_replace("\"", ",", $repuestos);
		$repuestos = str_replace("´", ",", $repuestos);
		$repuestos = str_replace("·", ",", $repuestos);
		$repuestos = str_replace("#", ",", $repuestos);
		$repuestos = str_replace("(", ",", $repuestos);
		$repuestos = str_replace(")", ",", $repuestos);
		$repuestos = str_replace("=", ",", $repuestos);
		$repuestos = str_replace("[", ",", $repuestos);
		$repuestos = str_replace("]", ",", $repuestos);
		$repuestos = str_replace("^", ",", $repuestos);
		$repuestos = str_replace("{", ",", $repuestos);
		$repuestos = str_replace("}", ",", $repuestos);
		$repuestos = str_replace("\\", ",", $repuestos);
		$repuestos = str_replace("/", ",", $repuestos);
		$repuestos = str_replace("¨", ",", $repuestos);
		$repuestos = str_replace(",,,", ",", $repuestos);
		$repuestos = str_replace(",,", ",", $repuestos);
		$repuestos = trim($repuestos, ",");
		$repuestos = utf8_encode($repuestos);

		$repuestos = explode(",", $repuestos);

		foreach ($repuestos as $key => $value) {
			$repuestos[$key] = ucfirst($value);
		}
		
		return $repuestos;
	}


	function get_name_report($idprovncia, $mes, $anno) {
		$name = utf8_decode($name);

		$name = trim($name);
		$name = strtolower($name);
		$name = str_replace(" ", "-", $name);
		$name = str_replace("ñ", "n", $name);
		$name = str_replace("ç", "c", $name);
		$name = str_replace("á", "a", $name);
		$name = str_replace("é", "e", $name);
		$name = str_replace("í", "i", $name);
		$name = str_replace("ó", "o", $name);
		$name = str_replace("ú", "u", $name);
		$name = str_replace("à", "a", $name);
		$name = str_replace("à", "e", $name);
		$name = str_replace("à", "i", $name);
		$name = str_replace("à", "o", $name);
		$name = str_replace("à", "u", $name);
		$name = str_replace("ä", "a", $name);
		$name = str_replace("ä", "e", $name);
		$name = str_replace("ï", "i", $name);
		$name = str_replace("ö", "o", $name);
		$name = str_replace("ü", "u", $name);
		
		// $name = time() . "-" . $name;
		
		return $name;
	}
	
	function clip_text($text, $amount) {
		if (strlen($text) > $amount) {
			$text = substr($text, 0, $amount);
			$text = $text . "...";
		}

		return $text;
	}

	function escStr($str) {
  		return mysql_real_escape_string($str);
	}

	function formatPhone($phone) {
		$a = array();
		for ($i=0; $i < strlen($phone); $i++) {
			if($i % 3 == 0) {
				$a[] = " ";	
			}
			$a[] = $phone[$i]; 
		}
		$number=implode("",$a);  
		return trim($number);
	}

	function get_class_state_exhibition($state) {
		switch ($state) {
			case $state == 'Publicada':
				return "ePublic";
				break;
			case $state == 'Publicada con obras':
				return "ePublicWPic";
				break;
			case $state == 'Oculta':
				return 'eHidden';
				break;
			case $state == 'Inicializada':
				return 	'eInit';
				break;
				
				default: 
				return "";
				break;
		}
	}
	function get_hora_datetime($datetime) {
		$dataParts = explode(" ", $datetime);
		return substr($dataParts[1],0, -3);
	}

	function get_date_abr($datetime) {
			
		$months = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");

		$dataParts = explode(" ", $datetime);
		$fechaAviso = $dataParts[0];

		$diaAviso = substr($fechaAviso, -2);
		$mesAviso = substr($fechaAviso, 5, -3);
		$annoAviso = substr($fechaAviso, 2, -6);

		$annoActual = date("y");
		if($mesAviso[0] == 0) {
			$mesAviso = substr($fechaAviso, 6, -3);			
		}
		echo $annoAviso . $annoActual;
		return ($annoAviso == $annoActual) ?  $diaAviso . " " . $months[$mesAviso - 1]: $annoActual . " " . $months[$mesAviso - 1] . " " . $annoAviso;
	}

	function get_formatted_datetime($datetime) {
		$months = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
		$dataParts = explode(" ", $datetime);

		$annoActual = date("Y");
		$mesActual = date("m");
		$diaActual = date("d");
		$horaActual = date("H");
		$minutoActual = date("i");
	      

		$fechaAviso = $dataParts[0];
		$horaAviso = substr($dataParts[1],0, -3);

		echo $horaAviso;


		$fecha1 = new DateTime($datetime);
		$fecha2 = new DateTime($annoActual . "-" . $mesActual . "-" . $diaActual. " " . $horaActual . ":" . $minutoActual . ":" . $segundoActual);
		$fecha = $fecha1->diff($fecha2);
		printf('%d años, %d meses, %d días, %d horas, %d minutos', $fecha->y, $fecha->m, $fecha->d, $fecha->h, $fecha->i);
	}

	function get_formatted_date($date) {
		$dateParts = explode("-", $date);

		$y = trim($dateParts[0]);
		$m = trim($dateParts[1]);
		$d = trim($dateParts[2]);

		$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$week = array("Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "Sabado", "Domingo");
		
		$year = abs(intval($y));	
		$month = abs(intval($m));
		$month = $months[intval($month) - 1];
		$day = abs(intval($d));

		$weekday = $week[date(N, strtotime($year . "-" . $m . "-" . $day)) - 1]; 

		return $weekday . ", " . $day . "-" . $month . "-" . $year;		
	}

	function get_formatted_direction($id) {
		$q = "select Calle, Numero, Edificio, Piso, Localidad, Pais from exposiciones where IDExposicion = " . $id;	
		$result = mysql_query($q);
		$row = mysql_fetch_assoc($result);
		$direction = "";
		if($row["Edificio"]){ $direction .= "<strong>" . $row["Edificio"] . "</strong><br />"; }
		$direction .= $row["Calle"];
		if($row["Numero"]){ $direction .= ", " . $row["Numero"]; }
		if($row["Piso"]){ $direction .= ", " . $row["Piso"]; }
		$direction .=  " " . $row["Localidad"] . ". " . $row["Pais"];

		return $direction;
	}	

	function get_module($section) {
		$parts = explode("-", $section);
		
		$archive = $parts[0];
		$directory = $parts[1];
		
		$result = "modules/" . $directory . "/" . $archive . ".php";
		
		return (!$section) ? "modules/obras/listar.php" : $result ;
	}

	function get_picture($id, $module, $clip) {
		$q = "select Imagen from " . $module . " where ID" . ucwords(substr($module, 0, -$clip)) . " = " . $id;
		$result = mysql_query($q);
		$row = mysql_fetch_assoc($result);

		$image = trim($row["Imagen"]);
		
		return $image;
	}

	function get_pictures_exhibition($id) {
		$q = "select IDObra from obras_expuestas where IDExposicion = ". $id;
		$result = mysql_query($q);

		$idsSelected = array(); 

		while ($row = mysql_fetch_assoc($result)) {
				$idsSelected[] = $row["IDObra"];
		}

		return $idsSelected;
	}

	function get_view($section) {
		$parts = explode("-", $section);

		$archive = $parts[0];
		$directory = $parts[1];

		$result = "modules/" . $directory . "/" . $archive . ".view.php";
		
		return ($section == "") ? "modules/obras/listar.view.php" : $result ;
	}

	function order_array($a) {
		$b = array();
		foreach ($a as $i) { $b[] = $i; }
		
		return $b;
	}

	function recoger_detalle($datos) {
		$lineas_detalle_completas = array();
		$contador_detalles = 0;

		foreach ($datos['campo-elemento'] as $key => $value) {

			if($value != 0 && !is_array($value)) {
				// echo "<p style='background-color: green; color: yellow;'>" . $value . "</p>";
				$lineas_detalle_completas[$contador_detalles]['elemento'] = $value;
				$lineas_detalle_completas[$contador_detalles]['especificacion'] = $datos['campo-especificacion'][$key];
				$lineas_detalle_completas[$contador_detalles]['accion'] = $datos['campo-accion'][$key];

				if(is_array($datos['campo-repuesto'][$key])){
					$lineas_detalle_completas[$contador_detalles]['repuestos'] = get_repuestos_separados($datos['campo-repuesto'][$key][0]);	
				} else {
					$lineas_detalle_completas[$contador_detalles]['repuestos'] = $datos['campo-repuesto'][$key];
				}
				

				$contador_detalles++;

			} else if (is_array($value) && $value[0] != ""){
				// echo "<p style='background-color: green; color: yellow;'>" . $value[0] . "</p>";	
				$lineas_detalle_completas[$contador_detalles]['elemento'] = $value;
				$lineas_detalle_completas[$contador_detalles]['especificacion'] = $datos['campo-especificacion'][$key];
				$lineas_detalle_completas[$contador_detalles]['accion'] = $datos['campo-accion'][$key];
				$lineas_detalle_completas[$contador_detalles]['repuestos'] = get_repuestos_separados($datos['campo-repuesto'][$key][0]);																		
				$contador_detalles++;
			



			} else if($value == 0){
				// echo "<p style='background-color: red; color: yellow;'>" . $value . "</p>";	
			
			} else if(is_array($value) && $value[0] == ""){
				// echo "<p style='background-color: red; color: yellow;'>NUEVO CAMPO VACIO</p>";	
			}
		}
		return $lineas_detalle_completas;
		
	}

	function get_clasificaciones($detalles) {

		$elementos = array();
		$contador = 0;
		foreach ($detalles as $key => $value) {
			$elementos[$contador] = [];
			if(!is_array($value["elemento"])) {
				$elementos[$contador]['elemento'] = $value["elemento"];
				if(!is_array($detalles[$key]["especificacion"])) {
					$elementos[$contador]['especificacion'] = $detalles[$key]["especificacion"];
				} else {
					$elementos[$contador]['especificacion'] = 0;
				}
				$contador++;
			}
		}
		
		$clasificaciones = array();
		foreach ($elementos as $key => $value) {
			if($value['especificacion'] != 0) {

				if(!in_array(get_clasificacion($value['especificacion'], 'especificaciones_elemento'), $clasificaciones) && get_clasificacion($value['especificacion'], 'especificaciones_elemento') != "") {
					array_push($clasificaciones,  get_clasificacion($value['especificacion'], 'especificaciones_elemento'));
				}
				
			} else {
				if(!in_array(get_clasificacion($value['elemento'], 'elementos'), $clasificaciones) && get_clasificacion($value['elemento'], 'elementos') != "") {
					array_push($clasificaciones,  get_clasificacion($value['elemento'], 'elementos'));
				}
			}
		}

		return $clasificaciones;

	}

	function get_clasificacion($id, $tabla) {

		if($tabla == elementos) {
			$tabla = 'elementos';
			$campo_id = 'IDElemento';
		} else {
			$tabla = 'especificaciones_elemento';
			$campo_id = 'IDEspecificacionElemento';
		}

		$q = "select Clasificacion from " . $tabla . " where " . $campo_id . " = " . $id;
		$resultado = mysql_query($q);
		$row = mysql_fetch_assoc($resultado);

		return $row["Clasificacion"];
	}

	function get_idmatricula($dato, $mat) {
		$matricula = explode("-", $mat);
		return $matricula[$dato];

	}


	

?>