<?php
session_start();


if ($_SESSION["IDUsuario"] == null) {
  Header("Location: login.php");
}

  $section = $_GET["s"];

require_once("includes/database.inc.php");
require_once("includes/arrays.inc.php");
require_once("includes/functions.inc.php");


connect();

?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>IPMotor</title>
   
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/imagenes/logo-reducido-72x72.png" />  
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/imagenes/logo-reducido-114x114.png" />  
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/imagenes/logo-reducido-144x144.png" />  
    <!-- Fuente Roboto -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,500,500italic,700italic,400italic' rel='stylesheet' type='text/css'>

    <!-- Estilos generales -->
    <link rel="stylesheet" href="css/root.css"/>

    <!-- Estilos jQuery UI -->
    <link rel="stylesheet" href="css/plugins/jquery-ui/jquery-ui.css">
    
    <!-- Librerias JS -->
    <script src="js/lib/jquery.js"></script>
    <script src="js/lib/modernizr.js"></script>
    <script src="js/lib/ajax.js"></script>
    
</head>


  <body>
    <div class="user-area-outfocus-dropdown"></div>

      <section class="canvas">

        <?php require_once("public/header/header.php") ?>

        <div class="canvas-dinamic">
          <?php

            switch ($section) {
              case 'v':
                require_once("public/modules/visitas/list.php");
              break;
              case 't':
                require_once("public/modules/taller/list.php");
              break;
               case 'ta':
                require_once("public/modules/asistente/list.php");
              break;
               case 'p':
                require_once("public/modules/programming/list.php");
              break;
              case 'p1':
                require_once("public/modules/programming/unit.form.php");
              break;
              case 'p2':
                require_once("public/modules/programming/assistance.form.php");
              break;
              default:
                if($_SESSION["Nivel"] == "Taller" || $_SESSION["Nivel"] == "Pruebas Taller"){
                  require_once("public/modules/intervenciones/insert.php");
                } elseif($_SESSION["Nivel"] == "Gestor") {
                  require_once("public/modules/informes/list.gest.php");
                } elseif($_SESSION["Nivel"] == "Mantenedora") {

                  require_once("public/modules/informes/list.man.php");
                  if($_GET['d']){
                    $now = $_GET['d'];
                    require_once("public/modules/informes/gestorDescargas.php");
                  }
                }
                break;
            }

          ?>

        </div>

       

      </section>
  </body>
</html>
 
<?php desconnect(); ?>