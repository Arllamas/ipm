<?php

function connect() {
	

	$host = "localhost";
	$user = "root";
	$pwd = "";
	$db = "ipmotorv1";
	


	@mysql_connect($host, $user, $pwd) or die("No puedo conectar");
	mysql_select_db($db) or die("No encuentro la base de datos '" . $db . "'");
	
	mysql_query("SET NAMES 'utf8'");
}

function desconnect() {
	mysql_close();
}

?>