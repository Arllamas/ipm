
<a href="logout.php" class="user-area-header" title="Salir de la aplicación" style="right: 0;"><span class="user-area-header-text">
		<?php echo $_SESSION["Nombre"]; ?> <em class="user-area-header-profile">(<?php echo $_SESSION["Nivel"]; ?><?php if($_SESSION['Provincia']){ echo " " . $_SESSION['Provincia']; }?>)</em></span><span class="user-area-nav-icon icon-exit" style="font-size: 20px;"></span>
</a>


