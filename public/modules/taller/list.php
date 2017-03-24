<?php
	
	$q = "SELECT A.IDAviso, A.IDMatricula, A.FechaAviso, A.Aviso, M.IDUnidad, M.Matricula, U.Unidad, U.TipoUnidad ";
	$q .= "FROM avisos A, matriculas M, unidades U ";
	$q .="WHERE A.IDMatricula = M.IDMatricula && M.IDUnidad = U.IDUnidad ";
	$q .="ORDER BY Unidad ASC, FechaAviso DESC";

	$result = mysql_query($q);

	$qU = "SELECT * from unidades where IDProvincia =" . $_SESSION["IDProvincia"];

	$resultU = mysql_query($qU);


	require_once("public/modules/taller/list.tmp.php")

?>
