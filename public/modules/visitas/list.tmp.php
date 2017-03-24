<div class="second-nav">
	<p class="second-nav-title">Visitas Pendientes</p>
</div>

<div class="seccion-container">
	<div class="container-b fIU">
		<h3 class="fIU-list-title">VISITAS PENDIENTES</h3>
		<ol class="fIU-list fInformes-nav-list">
			<li class="fIU-list-item fInformes-nav-list-item">
				<label class="fIU-list-item-label" for="dt-notice" >Unidad:</label>
				<div class="fIU-list-item-container">
					<select name="" id="campo-unidad" class="fIU-list-item-select">
						<option value="0">Todos</option>
						<?php while ($regU = mysql_fetch_assoc($resultU)): ?>
							<option value="<?php echo $regU["IDUnidad"]; ?>"><?php echo $regU["Unidad"]; ?></option>	
						<?php endwhile; ?>
					</select>
				</div>
			</li>
		</ol>
	

		<ul class="lIU">
			<?php while($reg = mysql_fetch_assoc($result)):
					echo is_today($reg["FechaAviso"]);
					if(!$idunidad):

						$idunidad = $reg["IDUnidad"];
						$unidad = $reg["Unidad"]
					?>
					
					<li class="lIU-h"><?php echo $reg["TipoUnidad"] . " " . $reg["Unidad"]; ?></li>
					
					<?php else:

						if($idunidad != $reg["IDUnidad"]):

					?>
					
							<li class="lIU-h"><?php echo $reg["TipoUnidad"] . " " . $reg["Unidad"]; ?></li>
						<?php endif;?>	
					<?php endif;?>
					
			<li class="lIU-item">
				<ul class="lIU-item-list">
					<li class="lIU-item-list-item avisos-item avisos-item-fecha">
						<em>
							<?php if(is_today($reg["FechaAviso"]) == "h"): ?>  <?php  endif; ?>Hoy, 13:24
						</em>
					</li>

					<li class="lIU-item-list-item avisos-item avisos-item-mat">
						<?php echo $reg["Matricula"]; ?>
					</li>
					<li class="lIU-item-list-item avisos-item avisos-item-desc">
						<?php echo ucfirst($reg["Aviso"]); ?>
					</li>
					<li class="lIU-item-list-item avisos-item-menu">
						<ul class="lIU-menu avisos-item-menu-list">
							<li class="lIU-menu-item avisos-item-menu-item">
								<a class="icon-button" href="#"><span class="second-nav-icon icon_button_i icon-eye"></span> <span class="icon-button-text">Reparar</span></a>
							</li>
							<li class="lIU-menu-item avisos-item-menu-item">
								<a class="icon-button" href="#"><span class="second-nav-icon icon_button_i icon-eye"></span> <span class="icon-button-text">A taller</span></a>
							</li>
							<li class="lIU-menu-item avisos-item-menu-item">

								<a class="icon-button" href="#"><span class="second-nav-icon icon_button_i icon-eye"></span> <span class="icon-button-text">Posponer</span></a>
							</li>
						</ul>
					</li>
				</ul>
			</li>				
			<?php endwhile; ?>
		</ul>
	</div>
</div>