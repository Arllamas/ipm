<?php


$IDUnidad = abs(intval($_GET["u"]));


// Get unit selected
$qUnidad = "select TipoUnidad, Unidad from unidades where IDUnidad = " . $IDUnidad;

$result1 = mysql_query($qUnidad);

$row = mysql_fetch_assoc($result1);

$unit = $row["TipoUnidad"] . " " . $row["Unidad"];

// Get current datetime-local
$currentDT = explode("+",date(c));
$cdt = $currentDT[0];


$qMatricula = "select IDMatricula, Matricula from matriculas where IDUnidad = " . $IDUnidad  . " ";
$qMatricula .= "order by Matricula asc";

$result2 = mysql_query($qMatricula);
// Creo un array con todas las matrículas para poder usarlo en los distintos campos
while ($mat = mysql_fetch_assoc($result2)) {
	$matriculas[] = $mat["IDMatricula"] . "-" . $mat["Matricula"];
}


if ($_POST) {
	

	$arreglo = $_POST["regCar"];
	$contador = 0;
	if($arreglo[0] == 0) {
		foreach ($arreglo as $key=>$value) {

			if($value != 0) {
				$arreglo2[] = $value;
				$contador++;

			}
			$_POST["regCar"] = $arreglo2;
		} 
	}

	$id_provincia = $_SESSION["IDProvincia"];
	$id_matriculas = $_POST["regCar"];
	$descripciones = $_POST["descript"];
	$fecha_aviso = $_POST["dt-notice"];
	$totalAvisos = count($_POST["regCar"]);
	
	for ($i = 0; $i <= $totalAvisos - 1; $i++) {
		$qi = "INSERT INTO avisos (IDMatricula, IDProvincia, FechaAviso, Aviso) VALUES (";
		$qi .= $id_matriculas[$i] . ", ";
		$qi .= $id_provincia . ", ";
		$qi .= "'" . $fecha_aviso . "', ";
		$qi .= "'" . $descripciones[$i] . "')";

		$resulti = mysql_query($qi);

		if($resulti) {
			$id_aviso = mysql_insert_id();
			$qS="INSERT INTO seguimiento_intervencion (IDAviso, Fecha) VALUES (";
			$qS .= $id_aviso . ", ";
			$qS .= "'" . $fecha_aviso . "')";
			
			$resultS = mysql_query($qS);
		}
	}

	
}



// '2015-06-23 04:26:00' En este formato entra a la base de datos

// '2013-10-08T23:59' En este formato entra al balue del input


	require_once("public/modules/programming/unit.form.tmp.php");
	
?>