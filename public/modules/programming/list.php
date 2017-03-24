<?php


	$u = abs(intval($_GET["u"]));
	$q = "select TipoUnidad, Unidad, IDUnidad from unidades where IDUnidad = " . $u;

	$result = mysql_query($q);
	
 	if ($result) {
 		if (mysql_num_rows($result)) {
 			$row = mysql_fetch_assoc($result);
 			$IDUnidad = $row["IDUnidad"];
 			$unit = $row["TipoUnidad"] . " " . $row["Unidad"];
 		} 
 	}

	require_once("public/modules/programming/list.tmp.php");
?>


