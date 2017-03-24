<div class="second-nav">
	<p class="second-nav-title">GRABACIÓN DE INTERVENCIONES</p>
</div>

<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery.multiselect.filter.css" />


<script src="js/plugins/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="js/plugins/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="js/plugins/multiselect/jquery.multiselect.filter.js"></script>


<div class="seccion-container">
	<div class="container-b fIU">
		<form id="fU" method="POST" action="public/modules/intervenciones/insert.process.tmp.php">
			<h3 class="fIU-list-title">PARTE DE TRABAJO</h3>
			<ol class="fIU-list fIntervenciones-general-list">
					<!-- Campo de Fecha y Hora de asistencia -->
					<li class="fIU-list-item fIntervenciones-general-list-item">
						<label class="fIU-list-item-label" for="dt-parte" >Fecha y hora:</label>
						<div class="fIU-list-item-container">
							<input class="fIU-list-item-input" type="datetime-local" name="dt-parte" id="dt-parte" value="<?php echo $cdt; ?>" max="<?php echo $cdt; ?>" min="<?php echo $min_dt; ?>">
						</div>
					</li>

					<li class="fIU-list-item fIntervenciones-general-list-item">
						<label class="fIU-list-item-label" for="campo-unidad" >Unidad:</label>
						<div class="fIU-list-item-container">
							<select name="campo-unidad" id="campo-unidad" class="fIU-list-item-select">
								<option value="0">Elegir unidad</option>
								<?php
								// Se crea array para almacenar matrícuals y volverlas a mostrar en select de desplazamiento
								$matriculas = array();
								$contador = 0;
								?>
								<?php while($rowUnidad = mysql_fetch_assoc($resultUnidades)): ?>
								<?php
								$matriculas[$contador]['IDUnidad'] = $rowUnidad["IDUnidad"];
								$matriculas[$contador]['Unidad'] = $rowUnidad["Unidad"];
								$contador++;
								?>
										<option value="<?php echo $rowUnidad["IDUnidad"]; ?>"><?php echo $rowUnidad["Unidad"]; ?></option>
								<?php endwhile; ?>
								<?php if($rowR["IDUnidad"]): ?>
									<option value="<?php echo $rowR["IDUnidad"]; ?>" style="text-decoration: underline;">Reserva provincial</option>
								<?php endif; ?>
							</select>
						</div>
					</li>

					<li class="fIU-list-item fIntervenciones-general-list-item">
						<label class="fIU-list-item-label" for="campo-matricula" >Matrícula:</label>
						<div class="fIU-list-item-container">
							<select name="campo-matricula" id="campo-matricula" class="fIU-list-item-select input-disabled" disabled>
								<option value="0">Elegir matrícula</option>
							</select>
						</div>
					</li>
					<li class="fIU-list-item fIntervenciones-general-list-item">
						<label class="fIU-list-item-label" for="c-km" >Lectura c-KM:</label>
						<div class="fIU-list-item-container">
							<input class="fIU-list-item-input input-disabled" type="tel" maxlength="6" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))" name="c-km" id="c-km" disabled/>

						</div>
					</li>
					
				</ol>

				<div class="container-fIntervenciones">
					<ol class="fIntervenciones-tipo-list">
						<li class="fIU-list-item">
							<label class="fIU-list-item-label" >Motivo de intervención:</label>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" value="tipo-averia" id="tipo-averia" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-averia">Aviso de avería</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" id="tipo-asistencia" value="tipo-asistencia" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-asistencia">Asistencia en carretera</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" id="tipo-unidad" value="tipo-unidad" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-unidad">Aviso en unidad</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" id="tipo-preventivas" value="tipo-preventivas" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-preventivas">Act. preventivas</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" id="tipo-desplazamiento" value="tipo-desplazamiento" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-desplazamiento">Desplazamiento</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="tipo-parte" id="tipo-siniestro" value="tipo-siniestro" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="tipo-siniestro">Siniestro</label>
							</div>
						</li>
					</ol>

					<ol class="fIntervenciones-lugarReparacion-list">
						<li class="fIU-list-item">
							<label class="fIU-list-item-label" >Reparada en:</label>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="lugar-reparacion" id="lugar-unidad"  value="lugar-unidad" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="lugar-unidad">Unidad</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="lugar-reparacion" id="lugar-taller" value="lugar-taller" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="lugar-taller">Taller</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="lugar-reparacion" id="lugar-calle" value="lugar-calle" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="lugar-calle">Calle</label>
							</div>
							
						</li>
					</ol>

					<ol class="fIntervenciones-preventivas-list">
						<li class="fIU-list-item">
							<label class="fIU-list-item-label" >Actuaciones preventivas:</label>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-completo" value="preven-completo" id="preven-completo" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-completo">M. Preventivo</label>
							</div>

							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-lavado" value="preven-lavado" id="preven-lavado" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-lavado">Lavado</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-itv" value="preven-itv" id="preven-itv" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-itv">Pre-ITV + ITV</label>
							</div>
						</li>
					</ol>
					
					<ol class="fIntervenciones-preventivas-list otras">
						<li class="fIU-list-item">
							<label class="fIU-list-item-label" >Actuaciones preventivas:</label>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-neumaticos" value="preven-neumaticos" id="preven-neumaticos" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-neumaticos">Presión neumáticos</label>
							</div>

							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-frenos" value="preven-frenos" id="preven-frenos" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-frenos">Ajuste frenos</label>
							</div>
							
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="checkbox" name="preven-aceite" value="preven-aceite" id="preven-aceite" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="preven-aceite">Reponer aceite</label>
							</div>
							
						</li>
					</ol>
					
					<ol class="fIntervenciones-desplazamiento-list hidden">
						<li class="fIU-list-item">
							<label class="fIU-list-item-label" >Detalle desplazamiento:</label>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="despla-accion" value="despla-entrega" id="despla-entrega" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="despla-entrega">Entregado en:</label>
							</div>
							<div class="fIU-list-item-container radio">
								<input class="fIU-list-item-radio" type="radio" name="despla-accion" value="despla-recogida" id="despla-recogida" disabled/>
								<label class="fIU-list-item-radio-label input-disabled" for="despla-recogida">Recogido de:</label>
							</div>
							<div class="fIU-list-item-container">
								<select name="campo-des-unidades" id="campo-des-unidades" class="fIU-list-item-select">
									<option value="0">Elegir unidad</option>
									<?php foreach ($matriculas as $key => $value): ?>
										<option value="<?php echo $matriculas[$key]["IDUnidad"]; ?>"><?php echo $matriculas[$key]["Unidad"]; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</li>
					</ol>
				</div>
				<div style="clear:both;"></div>
				<div class="container-fDetalleInterv">
					<label class="fIU-list-item-label">Detalle de reparaciones:</label>
					<ol class="fIU-list fDetalleInterv-list">
					<!-- Campo de Fecha y Hora de asistencia -->
					<li class="fIU-list-item fDetalleInterv-list-item">
						<label class="fIU-list-item-label" >Elemento:</label>
						<div class="fIU-list-item-container">
							<select name="campo-elemento[]" class="campo-elemento fIU-list-item-select input-disabled" disabled>
							<option value="0">Elegir elemento</option>
								<optgroup label="ELEMENTOS MAS FRECUENTES">

									<?php foreach ($elementos_mas as $key => $value): ?>
										<option value="<?php echo $value['id']; ?>"><?php echo $value['nombre']; ?></option>
									<?php endforeach; ?>
							  	</optgroup>
					 			<optgroup label="TODOS LOS ELEMENTOS">

							    	<?php foreach ($elementos_todos as $key => $value): ?>
										<option value="<?php echo $value['id']; ?>"><?php echo $value['nombre']; ?></option>
									<?php endforeach; ?>

						 		</optgroup>
						 		<option value="vacio">Nuevo elemento</option>
							 </select>
						</div>
					</li>

					<li class="fIU-list-item fDetalleInterv-list-item">
						<label class="fIU-list-item-label" >Especificación:</label>
						<div class="fIU-list-item-container">
							<select name="campo-especificacion[]" class="fIU-list-item-select input-disabled campo-especificacion" disabled>
								<option value="0">Elegir especificación</option>
								<option value="vacio">Nueva especificación</option>
							 </select>
							 <input type="hidden" class="alternativo" name="campo-especificacion[]" value="0"/>
						</div>
					</li>

					<li class="fIU-list-item fDetalleInterv-list-item">
						<label class="fIU-list-item-label" >Acción:</label>
						<div class="fIU-list-item-container">
							<select name="campo-accion[]" class="fIU-list-item-select input-disabled campo-accion" disabled>
								<option value="0">Elegir acción</option>
								<option value="vacio">Nueva acción</option>
							 </select>
							 <input type="hidden" class="alternativo" name="campo-accion[]" value="0"/>
						</div>
					</li>
					<li class="fIU-list-item fDetalleInterv-list-item">
						<label class="fIU-list-item-label"  >Repuestos:</label>
						<div class="fIU-list-item-container">
							<select name="campo-repuesto[]" class="fIU-list-item-select input-disabled campo-repuesto" disabled>
								<option value="0">Elegir repuestos</option>
								<option value="vacio">Nuevo repuesto</option>
							</select>
							<input type="hidden" class="alternativo" name="campo-repuesto[]" value="0"/>
						</div>
					</li>
				</ol>
				<ol class="fIntervenciones-btm_detalle hidden">
					<li class="fIU-list-item fIntervenciones-btm_detalle-item">
						<button type="button" id="btn_detalle" class="fIU-list-item-label-btm-2">+ Nuevo detalle</button>					
					</li>
				</ol>
				</div>
						
			<input type="button" class="fIU-list-item-btm" id="btnw" value="Grabar intervención" />
		</form>
	</div>
</div>

<script>
	$('body > div.alert').remove();
</script>
<?php if($_SESSION["Aviso"] == 1): ?>
	<script>
		$('body').append('<div class="alert">');
		$('.alert').hide(); // Ocultar de momento
		$('.alert').append('<li class="alert-list-item">Preventivos y Lavados grabados correctamente</li>'); 
		$('.alert').addClass('success');

	</script>
<?php endif; ?>
<?php if($_SESSION["Aviso"] == 2): ?>
	<script>
		$('body').append('<div class="alert">');
		$('.alert').hide(); // Ocultar de momento
		$('.alert').append('<li class="alert-list-item">Desplazamiento grabado correctamente</li>'); 
		$('.alert').addClass('success');

	</script>
<?php endif; ?>

<?php if($_SESSION["Aviso"] == 3): ?>
	<script>
		$('body').append('<div class="alert">');
		$('.alert').hide(); // Ocultar de momento
		$('.alert').append('<li class="alert-list-item">Intervención grabada correctamente</li>'); 
		$('.alert').addClass('success');

	</script>
<?php

endif; ?>

<?php unset($_SESSION["Aviso"]); ?>

	<script>
		$(window).resize(function () {
				$(".alert").css({
						zIndex: 3,
						position: 'fixed',
						// Se alinea en la horizontal cogiendo medidas del navegador 
						left: ($(window).width() - $('.alert').outerWidth()) / 2,
						top: ($(window).height() - $('.alert').outerHeight() - 20)

				});

				
			});
		// El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
		// Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
		$(".alert").fadeIn(500, function(){
			$('.alert').delay(5000 * $('.alert-list-item').size())
			.fadeOut(500);
		});	
			// Ejecutar la función que muestra y oculta las alertas
			$(window).resize();	
	</script>
		

<script src="js/controllers-view/modules/intervenciones/validate/fIntervenciones-val.js"></script>

<!-- Anular boton 'IR/GO' Iphone && Ipad -->
<script language="javascript" type="text/javascript">
   <!--disable enter key / go button iphone-->
   function stopRKey(evt) {
      var evt = (evt) ? evt : ((event) ? event : null);
      var node = (evt.target) ? evt.target : 
                               ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
      if ((evt.keyCode == 13) && (node.type=="email")) {return false;}
      if ((evt.keyCode == 13) && (node.type=="tel")) {return false;}
      if ((evt.keyCode == 13) && (node.type=="number")) {return false;}
   }

   document.onkeypress = stopRKey; 


</script>


