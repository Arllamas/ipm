<?php
session_start();

if ($_SESSION["IDUsuario"] != null) {
	Header("Location: index.php");
}



if ($_POST) {

	require_once("includes/database.inc.php");
	require_once("includes/functions.inc.php");
	require_once("includes/fnGenerarInformes.inc.php");
	
	connect();
	
	$login = trim($_POST["user"]);
	$password = $_POST["password"];

	$q = "select * from usuarios where ";
	$q .= " Usuario = '" . mysql_real_escape_string($login) . "' ";
	$q .= " and Password = '" . sha1($password) . "' ";
	
	$result = mysql_query($q);
	
	if (mysql_num_rows($result) == 1) {
		/*
		si hay exactamente 1 fila (y no puede haber más)
		significa que existe ese usuario + contraseña.
		*/

		// Busqueda de privilegios

		$user = mysql_fetch_assoc($result);

		$qp = "select * from privilegios where IDUsuario = " . $user["IDUsuario"];
		$resultp = mysql_query($qp);

		if($resultp && mysql_num_rows($resultp) == 1 && $user["Nivel"] != "Mantenedora") {

			$privilegio = mysql_fetch_assoc($resultp);
			$_SESSION["IDProvincia"] = $privilegio["IDProvincia"];			
			$_SESSION["Provincia"] = get_name_province($privilegio["IDProvincia"]);

		



		// Si el usuario es una mantenedora
		} else if ($user["Nivel"] == "Mantenedora") {
			$_SESSION["IDProvincia"] = array();
			while ($privilegio = mysql_fetch_assoc($resultp)) {
				$_SESSION["IDProvincia"][] = $privilegio["IDProvincia"];
			}

		// Generar y actualizar informes
		// Parametros actuales
		$mes_actual = date(m);
		$anno_actual = date(Y);

		$directorio_mantenedora = clean_name($user["Nombre"]);
		$ruta = 'almacen/' . $directorio_mantenedora;

		// Comprobar si existe directorio para la mantenedora
		// Si no existe se crea
		if (!file_exists($ruta) || !is_dir($ruta)) {
			mkdir($ruta, 0777);
		}
		
		// Abrir directorio de la mantenedora
		if ($aux = opendir($ruta)) {

			// Se crea un array con todos los subdirectorios
			$arr_dirs = array();
			while ($archivo = readdir($aux)) {
				if (is_dir($ruta . "/" . $archivo) && $archivo != "." && $archivo != "..") {
				
					$arr_dirs[] = $archivo;
					
			    }
			}

			// Comprobar que hay directorio para todas las provincias de la mantenedora
			// Si no existen se crea
			foreach ($_SESSION["IDProvincia"] as $idprovincia) {
				$nombre_provincia_mantenedora = clean_name(get_name_province($idprovincia));
				$aux_exist = false;
				
				// Recorrer todos los subdirectorios
				foreach ($arr_dirs as $direc) {

						// Si coincide con una provincia de la mantenedora cambiar estado de $aux_exist
						if ($direc == $nombre_provincia_mantenedora) {
							$aux_exist = true;
							break;
						}
				}	
				
				// Se comprueba que existe un $nombre_provincia_mantenedora
				// Evitar errores de la BD de que una mantenedora tenga dados privilegios a una provincia que no existe para la BD
				if ($nombre_provincia_mantenedora) {
					// Si hay directorio de provincia comprobamos y actualizamos archivos
					if ($aux_exist) {
						
						
						$ruta_subdirectorio = $ruta . "/" . $nombre_provincia_mantenedora . "/"; 

						$patron_glob = $ruta_subdirectorio  . "*.xls";

						// Comprobar nuevos informes 

							

						if (hayIntervenciones($idprovincia, $mes_actual, $anno_actual)) {
							create_report($directorio_mantenedora, $idprovincia, $mes_actual, $anno_actual, "0");

						}


						foreach (glob($patron_glob) as $file) {

							
							// Quitar extensión ".xls" a la cadena;
							$file = substr($file, 0, -4);

							$file_name = str_replace($ruta_subdirectorio, "", $file);
							
							
							// Calcular datos del mes anterior
							//Comprobar si es el primer mes del anno y cambiar el anno actual si es el caso			
							if (strlen($mes_pasado = $mes_actual - 1) == 1) {
									$mes_pasado = "0" . $mes_pasado;
							}

							
							// Informe del mes antorior
							$patron2 = "/^(" . $anno_actual . $mes_pasado  . ")/";

							if(preg_match($patron2, $file_name)) {

							    // Si termina en 0
								// No está finalizada	
							    if(substr($file_name, -1) == '0') {
							       	create_report($directorio_mantenedora, $idprovincia, $mes_pasado, $anno_actual, "1");
							    }

							}
							
						}

					// En caso contrario creamos directorio de provincia
					} else {
						if (hayIntervenciones($idprovincia, $mes_actual, $anno_actual)) {
							create_report($directorio_mantenedora, $idprovincia, $mes_actual, $anno_actual, "0");
						}
					}	
				}
			}	
		

		}
		

		} else {
			$_SESSION["Aviso"] = 1;
			Header("Location: login.php");
		}
		
		

		$IDSession = grabar_session(get_info_usuario(), $user["IDUsuario"]);
		$_SESSION["IDUsuario"] = $user["IDUsuario"];
		$_SESSION["IDSession"] = $IDSession;
		$_SESSION["Usuario"] = $user["Usuario"];
		$_SESSION["Nombre"] = $user["Nombre"];
		$_SESSION["Nivel"] = $user["Nivel"];
		
		// Borrar avisos de error si los tuviese
		unset($_SESSION["Aviso"]);
		Header("Location: index.php");
		
	} else {
		$_SESSION["Aviso"] = 1;
	}

	desconnect();

}

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <title>IPMotor</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/imagenes/logo-reducido-72x72.png" />  
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/imagenes/logo-reducido-114x114.png" />  
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/imagenes/logo-reducido-144x144.png" />  

	 <!-- Fuente Roboto -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,500,500italic,700italic,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/login.css"/>

	<script src="js/lib/jquery.js"></script>
	<script src="js/lib/modernizr.js"></script>


</head>

<body>
<section id="container">
	
	
	<form method="post" action="login.php" class="login">
		<h1 class="login-header"><span>IP MOTOR</span></h1>	
		<ol class="login-form">
			<li class="login-form-item">
				<label for="user" class="login-form-label icon-user"><span class="login-form-label-text">Usuario:</span></label>
				<input type="text" name="user" id="user" autocomplete="off" class="login-form-input" placeholder="Usuario"/>
			</li>
			<li class="login-form-item">
				<label for="password" class="login-form-label icon-key"><span class="login-form-label-text">Contraseña:</span></label>
				<input type="password" name="password" id="password" class="login-form-input" placeholder="Password"/>
			</li>
			<li class="login-form-item">
				<input class="login-form-buttom" type="submit" value="Entrar" />
			</li>
		</ol>
		
	</form>
	
</section>

</body>
</html>

<?php if($_SESSION["Aviso"] == 1): ?>
	<script>
		$('body').append('<div class="alert">');
		$('.alert').hide(); // Ocultar de momento
		$('.alert').append('<li class="alert-list-item">Usuario o contrase&ntilde;a incorrecto.</li>'); 
		$('.alert').addClass('error');
		$(".alert").fadeIn(500, function(){
			$('.alert').delay(5000 * $('.alert-list-item').size())
			.fadeOut(500);
		});
	</script>

<?php endif; ?>

<script type="text/javascript">
	// Función que muestra las alertas y oculta con transición de fundido
		$(window).resize(function () {
				$(".alert").css({
						zIndex: 3,
						position: 'fixed',
						// Se alinea en la horizontal cogiendo medidas del navegador 
						left: ($(window).width() - $('.alert').outerWidth()) / 2,
						top: ($(window).height() - $('.alert').outerHeight() - 20)

				});

				
			});
		
			// Ejecutar la función que muestra y oculta las alertas
			$(window).resize();		
</script>
<?php 	unset($_SESSION["Aviso"]);  ?>
