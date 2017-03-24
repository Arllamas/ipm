<a href="#" class="main-nav-button-dropdown icon-menu" style="font-size: 20px;"></a>
<a href="#" class="main-nav-button-dropdown icon_close" style="font-size: 24px; font-family: arial; font-weight: bold; position: relative; top: -10px;">x</a>


<ul class="main-nav">
	<?php if($_SESSION["Nivel"] == "Taller" || $_SESSION["Nivel"] == "Pruebas Taller"): ?>
	   <li class="main-nav-item <?php if($_GET['s'] != 'i' && $_GET['s'] != 'v'): ?>is-active<?php endif; ?>"><span class="main-nav-item-hover"></span><a href="index.php?s=p" class="main-nav-link">AVISOS</a></li>
	   <li class="main-nav-item <?php if($_GET['s'] == 'i'): ?>is-active<?php endif; ?>"><span class="main-nav-item-hover"></span><a href="index.php?s=v" class="main-nav-link">VISITAS</a></li>
	   <li class="main-nav-item <?php if($_GET['s'] == 'v'): ?>is-active<?php endif; ?>"><span class="main-nav-item-hover"></span><a href="index.php?s=t" class="main-nav-link">TALLER</a></li>
	<?php else: ?>
		<li class="main-nav-item is-active"><span class="main-nav-item-hover"></span><a href="index.php" class="main-nav-link">INFORMES</a></li>
	<?php endif; ?>
</ul>

