<?php
session_start();

// Si no hay usuario logueado para esta provincia redireccionar a login
if ($_SESSION["IDUsuario"] == null) {
	Header("Location: ../../../logout.php");
}


if($_SESSION["Nivel"] == 'Pruebas Taller'){
	$_SESSION["Aviso"] = 3;
	Header("Location: ../../../index.php");
} else {
	if ($_POST) {
		require_once("../../../includes/database.inc.php");
		require_once("../../../includes/functions.inc.php");
		connect();

		// Si el usuario no tiene privilegios para esta provincia redireccionar a login
		if ($_SESSION["IDProvincia"] != get_provincia_por_unidad($_POST["campo-unidad"])) {
			Header("Location: ../../../logout.php");

		}



		// Recoger datos generales

		$dt_parte = str_replace("T", " ", trim($_POST["dt-parte"]));
		$IDUnidad = abs(intval($_POST["campo-unidad"]));


		$campo_matricula = trim($_POST["campo-matricula"]);

		// LAVADOS O PREVENTIVOS A varios vehículos
		if($campo_matricula == 'all') {
			
		$tipo_parte = trim($_POST["tipo-parte"]);	
		$lugar_reparacion = trim($_POST["lugar-reparacion"]);
			

			if($tipo_parte == 'tipo-preventivas' && $lugar_reparacion == 'lugar-unidad') {
				$filasVehiculos[]=array();
				
				foreach ($_POST['c-km'] as $key => $value) {
					$lav = explode('-', $_POST['L'][$key]);
					$prev = explode('-', $_POST['P'][$key]);

					$matricula_lav = abs(intval($lav[1]));
					$matricula_prev = abs(intval($prev[1]));
					

					if(($matricula_lav && $matricula_prev && ($matricula_lav == $matricula_prev)) || ($matricula_lav && !$matricula_prev) || (!$matricula_lav && $matricula_prev) || ($matricula_lav == 0 && $matricula_prev) || ($matricula_lav && $matricula_prev  == 0)) {
						$filasVehiculos[$key] = "";

						if($matricula_lav){
							$filasVehiculos[$key] .= $lav[0] . "-";
						}
						if($matricula_prev) {
							$filasVehiculos[$key] .= $prev[0] . "-";	
						}

						if($matricula_lav) {
							$row_mat = $matricula_lav;
						} else if($matricula_prev) {
							$row_mat = $matricula_prev;
						}

						$filasVehiculos[$key] .= $row_mat . "-";

						if($_POST['c-km']) {
							$filasVehiculos[$key] .= $_POST['c-km'][$key];					
						}
					} else if(!$matricula_prev && !$matricula_lav) {
						// No se añade linea

					} else {
						Header("Location: ../../../logout.php");
					}
					

				}
				$filasVehiculos = array_values($filasVehiculos);

				foreach ($filasVehiculos as $key => $value) {
					$lav_i = false;
					$prev_i = false;
					$IDMatricula = false;
					$km_mat = false;
					
					$rowintervencionAll = explode("-", $value);

					if($rowintervencionAll[0] == 'L'){
						$lav_i = true;
					}
					if($rowintervencionAll[0] == 'P') {
						$prev_i = true;
						$IDMatricula = $rowintervencionAll[1];
						if($rowintervencionAll[2]) {
							$km_mat = $rowintervencionAll[2];
						}


					} else if($rowintervencionAll[1] == 'P') {
						$prev_i = true;
						$IDMatricula = $rowintervencionAll[2];
						if($rowintervencionAll[2]) {
							$km_mat = $rowintervencionAll[3];
						}
					} else {
						$IDMatricula = $rowintervencionAll[1];
						if($rowintervencionAll[2]) {
							$km_mat = $rowintervencionAll[2];
						}
					}


					$qALL = "INSERT INTO intervenciones (`IDIntervencion`,`IDControlSesion`, `FechaIntervencion`, `IDUnidad`, `IDMatricula`, `Kilometros`, `MotivoIntervencion`, `LugarIntervencion`, `MPreventivo`, `ITV`, `Lavado`, `AjFrenos`, `PrNeumaticos`, `NivelAceite`, `Entregado`, `Recogido`, `IDUnidadDestino`, `RMecanica`, `RElectrica`, `RRuedas`, `RCarroceria`, `RCofre`, `ROtras`) VALUES (NULL, ";

					$qALL .= $_SESSION['IDSession'] .", "; // IDSession
					$qALL .= "'". $dt_parte ."', "; // Fecha y hora
					$qALL .= $IDUnidad . ", "; // IDUnidad
					$qALL .= $IDMatricula . ", "; // IDMatricula

					if($km_mat) {
						$qALL .= $km_mat . ", "; // Kilometros
					} else {
						$qALL .= "NULL, "; // Kilometros
					}
					$qALL .= "'preventivos', 'unidad', "; // Fijos
					if($prev_i) {
						$qALL .= "'1', "; // Preventivo
					} else {
						$qALL .= "NULL, ";
					}
					$qALL .= "NULL, ";
					if($lav_i) {
						$qALL .= "'1', "; // Lavado
					} else {
						$qALL .= "NULL, ";
					}
					$qALL .= "NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
			

					$resultAll = mysql_query($qALL);

				}

				if($resultAll) {
					$_SESSION["Aviso"] = 1;
					Header("Location: ../../../index.php");	
				}
				
				

			} else {
				// Se han saltado la validación
				 Header("Location: ../../../logout.php");
			}

		// Intervención a un único vehñiculo
		} else {
		// El valor de campo-marícula númerico
		// Lo recogemos

		$IDMatricula = abs(intval($_POST["campo-matricula"]));
			
			if($IDMatricula != 0) {

				// Intervención a una matrícula
				$c_km = abs(intval($_POST['c-km']));

				if($_POST["tipo-parte"] == 'tipo-desplazamiento'){

					
				$despla_accion = trim($_POST['despla-accion']);
				$campo_des_unidades = abs(intval($_POST["campo-des-unidades"]));
				

					$qDES = "INSERT INTO intervenciones (`IDIntervencion`, `IDControlSesion`, `FechaIntervencion`, `IDUnidad`, `IDMatricula`, `Kilometros`, `MotivoIntervencion`, `LugarIntervencion`, `MPreventivo`, `ITV`, `Lavado`, `AjFrenos`, `PrNeumaticos`, `NivelAceite`, `Entregado`, `Recogido`, `IDUnidadDestino`, `RMecanica`, `RElectrica`, `RRuedas`, `RCarroceria`, `RCofre`, `ROtras`) VALUES (NULL, ";

						$qDES .= $_SESSION['IDSession'] .", "; // IDSession
						$qDES .= "'". $dt_parte ."', "; // Fecha y hora
						$qDES .= $IDUnidad . ", "; // IDUnidad
						$qDES .= $IDMatricula . ", "; // IDMatricula

						if($c_km) {
							$qDES .= $c_km . ", "; // Kilometros
						} else {
							$qDES .= "NULL, "; // Kilometros
						}
						$qDES .= "'desplazamiento', NULL, "; // Fijos
						$qDES .= "NULL, "; // Preventivo
						$qDES .= "NULL, "; // ITV
						$qDES .= "NULL, "; // Lavado
						$qDES .= "NULL, NULL, NULL, ";
						if($despla_accion == 'despla-entrega') {
							$qDES .= "'1', "; // Entregado
						} else {
							$qDES .= "NULL, ";
						}
						if($despla_accion == 'despla-recogida') {
							$qDES .= "'1', "; // Recogido
						} else {
							$qDES .= "NULL, ";
						}
						$qDES .= $campo_des_unidades . ", "; // IDUnidadDestino
						$qDES .= "NULL, NULL, NULL, NULL, NULL, NULL)";
				
						$resultDesplazamiento = mysql_query($qDES);

						if($resultDesplazamiento) {
							$_SESSION["Aviso"] = 2;
							Header("Location: ../../../index.php");	
						}
					} else {

						$tipo_parte = trim($_POST["tipo-parte"]);

						switch ($tipo_parte) {
							case 'tipo-averia':
								$tipo_parte = "avisoAveria";
								break;
							case 'tipo-unidad':
								$tipo_parte = "avisoUnidad";
								break;
							case 'tipo-preventivas':
								$tipo_parte = "preventivos";
								break;
							case 'tipo-asistencia':
								$tipo_parte = "asistencia";
								break;
							case 'tipo-siniestro':
								$tipo_parte = "siniestro";
								break;
						}

						$lugar_reparacion = trim($_POST["lugar-reparacion"]);
						$lugar = explode("-", $lugar_reparacion);
						$lugar_reparacion = $lugar[1];
						// $correc_mecanica = trim($_POST["correc-mecanica"]);
						// $correc_electrica = trim($_POST["correc-electrica"]);
						// $correc_ruedas = trim($_POST["correc-neumaticos"]);
						// $correc_carroceria = trim($_POST["correc-carroceria"]);
						// $correc_cofre = trim($_POST["correc-cofre"]);
						// $correc_otras = trim($_POST["correc-otras"]);
						$preven_completo = trim($_POST["preven-completo"]);
						$preven_lavado = trim($_POST["preven-lavado"]);
						$preven_itv = trim($_POST["preven-itv"]);
						$preven_neumaticos = trim($_POST["preven-neumaticos"]);
						$preven_frenos = trim($_POST["preven-frenos"]);
						$preven_aceite = trim($_POST["preven-aceite"]);



						$lineas_detalle_completas = recoger_detalle($_POST);
						$clasificaciones = get_clasificaciones($lineas_detalle_completas);

						$qINT = "INSERT INTO intervenciones (`IDIntervencion`,`IDControlSesion`, `FechaIntervencion`, `IDUnidad`, `IDMatricula`, `Kilometros`, `MotivoIntervencion`, `LugarIntervencion`, `MPreventivo`, `ITV`, `Lavado`, `AjFrenos`, `PrNeumaticos`, `NivelAceite`, `Entregado`, `Recogido`, `IDUnidadDestino`, `RMecanica`, `RElectrica`, `RRuedas`, `RCarroceria`, `RCofre`, `ROtras`) VALUES (NULL, ";

						$qINT .= $_SESSION['IDSession'] .", "; // IDSession
						$qINT .= "'". $dt_parte ."', "; // Fecha y hora
						$qINT .= $IDUnidad . ", "; // IDUnidad
						$qINT .= $IDMatricula . ", "; // IDMatricula

						if($c_km) {
							$qINT .= $c_km . ", "; // Kilometros
						} else {
							$qINT .= "NULL, "; // Kilometros
						}
						$qINT .= "'" . $tipo_parte . "', ";
						$qINT .= "'" . $lugar_reparacion . "', "; // Fijos
						if($preven_completo) {
							$qINT .= "'1', "; // Preventivo
						} else {
							$qINT .= "NULL, ";
						}
						if($preven_itv) {
							$qINT .= "'1', "; // ITV
						} else {
							$qINT .= "NULL, ";
						}
						if($preven_lavado) {
							$qINT .= "'1', "; // Lavado
						} else {
							$qINT .= "NULL, ";
						}
						if($preven_frenos) {
							$qINT .= "'1', "; // AjFrenos
						} else {
							$qINT .= "NULL, ";
						}
						if($preven_neumaticos) {
							$qINT .= "'1', "; // PrNeumaticos
						} else {
							$qINT .= "NULL, ";
						}
						if($preven_aceite) {
							$qINT .= "'1', "; // NiveAceite
						} else {
							$qINT .= "NULL, ";
						}
						$qINT .= "NULL, "; // Entregado
						$qINT .= "NULL, "; // Recogido
						$qINT .= "NULL, "; // IDUnidadDestino
						if(in_array('mecanica', $clasificaciones) || count($clasificaciones) == 0) {
							$qINT .= "'1', "; // R Mecanico
						} else {
							$qINT .= "NULL, ";
						}
						if(in_array('electrica', $clasificaciones)) {
							$qINT .= "'1', "; // R Electrica
						} else {
							$qINT .= "NULL, ";
						}
						if(in_array('ruedas', $clasificaciones)) {
							$qINT .= "'1', "; // R Ruedas
						} else {
							$qINT .= "NULL, ";
						}
						if(in_array('carroceria', $clasificaciones)) {
							$qINT .= "'1', "; // R Carroceria
						} else {
							$qINT .= "NULL, ";
						}
						if(in_array('cofre', $clasificaciones)) {
							$qINT .= "'1', "; // R Cofre
						} else {
							$qINT .= "NULL, ";
						}
						if(in_array('otras', $clasificaciones)) {
							$qINT .= "'1')"; // R Otros
						} else {
							$qINT .= "NULL)";
						}
						
				
						$resultIntervecion = mysql_query($qINT);
						if($resultIntervecion) {
							$IDIntervencion = mysql_insert_id();
						} else {
							Header("Location: ../../../logout.php");
						}
						

						foreach ($lineas_detalle_completas as $key => $value) {
							$qDET = "INSERT INTO `detalle_intervenciones` (`IDDetalleIntervencion`, `IDIntervencion`, `IDElemento`, `IDEspecificacionElemento`, `IDAccionElemento`, `ElementoNuevo`, `EspecificacionNueva`, `AccionNueva`) VALUES (NULL, ";
							$qDET .= $IDIntervencion . ", ";
							if(!is_array($value['elemento']) && $value['elemento'] != 0) {
								$qDET .= $value['elemento'] . ", ";	
							} else {
								$qDET .= "NULL, ";
							}
							if(!is_array($value['especificacion']) && $value['especificacion'] != 0) {
								$qDET .= $value['especificacion'] . ", ";	
							} else {
								$qDET .= "NULL, ";
							}
							if(!is_array($value['accion']) && $value['accion'] != 0) {
								$qDET .= $value['accion'] . ", ";	
							} else {
								$qDET .= "NULL, ";
							}
							if(is_array($value['elemento']) && $value['elemento'][0] != "") {
								$qDET .= "'" . $value['elemento'][0] . "', ";	
							} else {
								$qDET .= "NULL, ";
							}
							
							if(is_array($value['especificacion']) && $value['especificacion'][0] != "") {
								$qDET .= "'" . $value['especificacion'][0] . "', ";	
							} else {
								$qDET .= "NULL, ";
							}

							if(is_array($value['accion']) && $value['accion'][0] != "") {
								$qDET .= "'" . $value['accion'][0] . "')";	
							} else {
								$qDET .= "NULL)";
							}
							
							$resultDetalle = mysql_query($qDET);

							if($resultDetalle) {
								$IDDetalleIntervencion = mysql_insert_id();

								if(!is_array($value['repuestos']) && $value['repuestos'] != 0) {

									$qREP = "INSERT INTO `repuestos_detalle_intervencion` (`IDRepuestoDetInt`, `IDDetalleIntervencion`, `IDRepuesto`, `NuevoRepuesto`) VALUES (NULL, ";
									$qREP .= $IDDetalleIntervencion . ", ";
									$qREP .= $value['repuestos'] . ", ";	
									$qREP .= "NULL)";
									
									$resultadoREP = mysql_query($qREP);
								} else if(is_array($value['repuestos']) && count($value['repuestos']) > 0) {
									
									foreach ($value['repuestos'] as $k => $v) {
										$qREP = "INSERT INTO `repuestos_detalle_intervencion` (`IDRepuestoDetInt`, `IDDetalleIntervencion`, `IDRepuesto`, `NuevoRepuesto`) VALUES (NULL, ";
										$qREP .= $IDDetalleIntervencion . ", ";
										$qREP .= "NULL, ";	
										$qREP .= "'" . $v ."')";
										
										$resultadoREP = mysql_query($qREP);
									}
									
								}


								
								

							}
						}

						if($resultIntervecion) {
							$_SESSION["Aviso"] = 3;
							Header("Location: ../../../index.php");
						}
					}

			} else {

				// Se han saltado la validación
				 Header("Location: ../../../logout.php");
			}
		}

		}
} 

desconnect();
?>
