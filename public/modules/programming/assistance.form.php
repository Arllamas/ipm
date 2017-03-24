<?php

$IDUnidad = abs(intval($_GET["u"]));

$q = "select TipoUnidad, Unidad from unidades where IDUnidad = " . $IDUnidad;

$result = mysql_query($q);

$row = mysql_fetch_assoc($result);
$unit = $row["TipoUnidad"] . " " . $row["Unidad"];


require_once("public/modules/programming/assistance.form.tmp.php");

?>