<?php

require_once("../../../includes/database.inc.php");

connect();

$text = strtolower(trim($_POST["text"]));

$keys = explode( " " , $text);



$q = "select IDUnidad, TipoUnidad, Unidad from unidades where IDProvincia =" . $_SESSION["Provincia"];

for ($i=0; $i < count($keys); $i++) { 	
	if(($i < count($keys) - 1 || $i == 0)){
		$q .= "(LOWER(Unidad) like '%" . $keys[$i] . "%' AND ";
		$q .= "LOWER(TipoUnidad) like '%" . $keys[$i+1] . "%' ) OR ";
		$q .= "(LOWER(Unidad) like '%" . $keys[$i+1] . "%' AND ";
		$q .= "LOWER(TipoUnidad) like '%" . $keys[$i] . "%')  " ;
		if(count($keys) != 1 && count($keys) < $i - 1 ) {
			$q .= "OR ";
		}
	}
}

$q .= "order by Unidad ASC LIMIT 0,3";

$result = mysql_query($q);

if ($result) {
	$has = mysql_num_rows($result);	
}

desconnect();

require_once ("search.tmp.php");

?>