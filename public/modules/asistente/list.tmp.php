<div class="second-nav">
	<p class="second-nav-title">Asistente de programación</p>
</div>

<div class="seccion-container">
	<div class="container-b fIU">
		<h3 class="fIU-list-title">PLANIFICACION ITVs Y PREVENTIVOS <?php echo $months[$mes-1] . " " . $anno; ?></h3>
		<ol class="fIU-list-horizontal fInformes-nav-list">
			<li class="fIU-list-item-left fInformes-nav-list-item">
				<label class="fIU-list-item-left-label" for="dt-notice" >MES</label>
				<div class="fIU-list-item-container">
					<select name="" id="campo-unidad" class="fIU-list-item-select">
						<option value="0">Todos</option>
						<?php while ($regU = mysql_fetch_assoc($resultU)): ?>
							<option value="<?php echo $regU["IDUnidad"]; ?>"><?php echo $regU["Unidad"]; ?></option>	
						<?php endwhile; ?>
					</select>
				</div>
			</li>
			<li class="fIU-list-item-right fInformes-nav-list-item">
				<label class="fIU-list-item-left-label" for="dt-notice" >Filtro:</label>
				<div class="fIU-list-item-left-label-container">
					<select name="" id="campo-unidad" class="fIU-list-item-select">
						<option value="0">Todos</option>
						<?php while ($regU = mysql_fetch_assoc($resultU)): ?>
							<option value="<?php echo $regU["IDUnidad"]; ?>"><?php echo $regU["Unidad"]; ?></option>	
						<?php endwhile; ?>
					</select>
				</div>
				<div class="fIU-list-item-left-label-container">
					<select name="" id="campo-unidad" class="fIU-list-item-select">
						<option value="0">Todos</option>
						<?php while ($regU = mysql_fetch_assoc($resultU)): ?>
							<option value="<?php echo $regU["IDUnidad"]; ?>"><?php echo $regU["Unidad"]; ?></option>	
						<?php endwhile; ?>
					</select>
				</div>
			</li>
		</ol>
	

		<ul class="lIU" >
			<?php if (count($planificaciones_preparada) > 0): ?>
				<?php foreach ($planificaciones_preparada as $key => $planificacion): ?>
				


					<?php if(!$idunidad): ?>

						<?php

							$idunidad = $planificacion["IDUnidad"];

							$unidad = $planificacion["TipoUnidad"] . " " . $planificacion["Unidad"];
						?>	


						<li class="lIU-item">

							<h3 class="lIU-h"><?php echo $unidad; ?></h3>

							
					<?php else: ?>


						<?php if($idunidad != $planificacion["IDUnidad"]): ?>
								</li>
			
								
						<?php

							$idunidad = $planificacion["IDUnidad"];

							$unidad = $planificacion["TipoUnidad"] . " " . $planificacion["Unidad"];
						?>	
								<li class="lIU-item">
									<h3 class="lIU-h"><?php echo $unidad; ?></h3>

						<?php endif; ?>

						
											

					<?php endif; ?>
					
					<ul class="lIU-item-list">
						<li class="lIU-item-list-item reg-item-mat">
							<span class="mat-icon icon-mat"><span>E</span></span>
							<span class="mat-textnum"><?php 
									$num_matricula = substr($planificacion["Matricula"], 0, -3);
									$let_matricula = substr($planificacion["Matricula"], 4);

									echo $num_matricula . " " . $let_matricula; 
								?>
							</span>
						</li>
						<li class="lIU-item-list-item reg-item-menu">

							<div class="reg-item-tipo">
							
								<?php foreach ($planificacion["Tipo"] as $key => $tipo): ?>
									<?php

									switch ($tipo["Tipo"]) {
										case 'preventivo':
											$tipo_plan = "PREV";

											break;
										case 'itv':
											$tipo_plan = "ITV";
											break;
										
									}


									?>
								
									<span class="reg-item-tipo-<?php echo $tipo["Tipo"]; ?>" 
									
										data-idplanificacion="<?php echo $tipo["IDPlanificacion"]; ?>"
										
								
								    ><?php echo $tipo_plan; ?></span>
								<?php endforeach; ?>
							</div>
							
						</li>
					</ul>	

				
				<?php endforeach; ?>
			<?php else: ?>
				<li class="lIU-item">
				<em >Todavía no hay planificación para este mes</em>
				</li>
			<?php endif; ?>


				

			</li>			
		</ul>
	</div>
</div>

<script src="js/controllers-view/modules/asistente/asistente.js"></script>
<script src="js/controllers-view/modules/asistente/cabecera-unidades.js"></script>
<script src="js/controllers-view/modules/asistente/add-date-input.js"></script>

<script src="js/controllers-view/modules/asistente/etiqueta-tipo-itv.js"></script>
<script src="js/controllers-view/modules/asistente/etiqueta-tipo-preventivo.js"></script>
<script src="js/controllers-view/modules/asistente/etiquetas-tipos.js"></script>
