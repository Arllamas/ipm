<div class="second-nav">
	<a class="second-nav-breadcrumb" href="index.php?s=p&u=<?php echo $IDUnidad; ?>"><span class="second-nav-breadcrumb-icon icon-pin"></span> <?php echo $unit; ?></a>

	<h2 class="second-nav-title"><span class="<?php if($_GET['s'] == 'p1' || $_GET['s'] == 'p2'): ?> second-nav-title-p<?php endif; ?>">PROGRAMAR ACTUACIONES EN UNIDAD</span></h2>
</div>




<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="css/plugins/multiselect/jquery.multiselect.filter.css" />


<script src="js/plugins/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="js/plugins/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="js/plugins/multiselect/jquery.multiselect.filter.js"></script>


<div class="seccion-container">
	<div class="container-b fIU">
		<form id="fU" method="POST" action="index.php?s=p1&u=<?php echo $IDUnidad; ?>">
<!-- Columna izquierda del formulario -->
			<div class="fUnit-colLeft">
<!-- Titulo -->
				<h3 class="fIU-list-title">REGISTRAR AVISO EN UNIDAD</h3>
<!-- Campos de la columna izquierda -->
				<ol class="fIU-list">
<!-- Campo de Fecha y Hora de asistencia -->
					<li class="fIU-list-item fUnit-item">
						<label class="fIU-list-item-label" for="dt-notice" >Fecha y hora aviso:</label>
						<div class="fIU-list-item-container">
							<input class="fIU-list-item-input" type="datetime-local" name="dt-notice" id="dt-notice" value="<?php echo $cdt; ?>" max="<?php echo $cdt; ?>">
						</div>
					</li>
					<div style="clear: both;"></div>

<!-- Campos primer aviso -->
					<li class="fUnit-fNotice">
						<ol>
							<li class="fIU-list-item fUnit-item-reg">	
								<label class="fIU-list-item-label fUnit-item-reg-first-label">#</label>
								<div class="fIU-list-item-container">
									<select name="regCar[]" class="fIU-list-item-select select-mat">
										<option value="0">Matrícula</option>
										<?php foreach ($matriculas as &$valor): ?>
    										<option value="<?php echo get_idmatricula(0, $valor); ?>"><?php echo  get_idmatricula(1, $valor); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</li>
<!-- Campo primera descripción de avería -->
							<li class="fIU-list-item fUnit-item">
<!-- Etiqueta descripción de avería con botton -->
								<label class="fIU-list-item-label" >Descripción<span class="smartphones-off">de la avería:  </span><button type="button"  class="fIU-list-item-label-btm add-buttom">+<span class="add-text"> Añadir matrícula</span></button></label>
								<div class="fIU-list-item-container">
									<textarea name="descript[]" class="fIU-list-item-textarea fUnit-item-textarea input-disabled" disabled></textarea>
								</div>
							</li>	

						</ol>
<!-- Label oculto para centrar primer select matrícula -->
					</li>
				</ol>
			</div>




<!-- Columna derecha del formulario -->
			<div class="fUnit-colRight">
<!-- Titulo -->
				<h3 class="fIU-list-title">OTRAS ACTUACIONES</h3>
<!-- Campos de la columna derecha -->			
				<ol>
<!-- Campo de preventivos y lavados -->
					<li class="fIU-list-item">
						<label for="prevAndwash" class="fIU-list-item-label">Preventivos y lavados:</label>
						<div class="fIU-list-item-container">
							<select id="prevAndwash" name="prevAndwash[]" multiple="multiple" style="width:370px !important;">
							<optgroup label="PREVENTIVOS">
								<?php foreach ($matriculas as &$valor): ?>
    								<option value="P<?php echo $valor; ?>"><?php echo $valor; ?></option>
								<?php endforeach; ?>
							</optgroup>
							<optgroup label="LAVADOS">
								<?php foreach ($matriculas as &$valor): ?>
    								<option value="L<?php echo $valor; ?>"><?php echo $valor; ?></option>
								<?php endforeach; ?>
							</optgroup>
						</select>
						</div>
					</li>

<!-- Campo de selección de matrícula para ITV -->	
					<li class="fIU-list-item">
						<label class="fIU-list-item-label" for="matITV">ITV:</label>
						<div class="fIU-list-item-container">
							<select id="matITV" multiple="multiple" name="matITV[]" class="fIU-list-item-select">
								<?php foreach ($matriculas as &$valor): ?>
    								<option value="<?php echo $valor; ?>"><?php echo $valor; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</li>
<!-- Campo de fecha de cita ITV -->	
					<li class="fIU-list-item">
						<label class="fIU-list-item-label" for="datetimeITV">Fecha cita ITV:</label>
						<div class="fIU-list-item-container">
							<input type="datetime-local" id="datetimeITV" name="datetimeITV" class="fIU-list-item-input" value="0" min="<?php echo $cdt; ?>">
						</div >
					</li>
				</ol>
			</div>


			<input type="button" class="fIU-list-item-btm" id="btnw" value="Grabar actuaciones" />
		</form>
	</div>
</div>
	


<script type="text/javascript">

	$( document ).ready(function() {

		// Script - Selector de preventivos y lavados
		$("#prevAndwash").multiselect().multiselectfilter();
		$("#matITV").multiselect();

		  
		<?php 
		//Comprobamos si hay matriculas asignadas a la unidad 
		if(count($matriculas) > 0): ?>
			
			var matriculas = [];
			var idmatriculas = [];
			<?php 
			// Pasamos el array de matrículas a javascript
			foreach ($matriculas as &$valor): ?>
				matriculas.push(['<?php echo get_idmatricula(1, $valor); ?>','<?php echo get_idmatricula(0, $valor); ?>'] );
				
			<?php endforeach; ?>

		

			// Funcion para rellenar select con los option que no están ya seleccionados en otros select
			$.fillOptions = function() {

			// Hacemos otra copia del array de matrículas
			matriculasPROV = matriculas;

			
			// Bucle con las matrículas que hay seleccionadas en todos los select de matrícula
				$('.select-mat').find('option:selected').not('[value=0]').each(function(i, v) {
					
					//Quitamos al array clonado de matrículas las ya seleccionadas 
					
					var matt = $(this).text();
					matriculasPROV = $.grep(matriculasPROV, function(value){
						return value[0] != matt;
					});


			});
			// Borramos todos los options del select menos el seleccionado y el default
			$('.select-mat').parent().find('option').not('option:selected').not('option[value=0]').remove();
			// Añadimos los nuevos options con las matrículas que quedan en el array matriculasPROV
			$.each(matriculasPROV, function(i2, v2){
				// var result = v2.split('-');

				$('.select-mat').append('<option value="'+v2[1]+'">'+v2[0]+'</option>');
			});
		}

	
		// Evento al seleccionar una matrícula 
		 $(".select-mat").on("change", function() {

		 	// Si se selecciona una matrícula
			if($(this).find('option:selected').val() != 0) {
				//Activamos el textarea asociado
		 		$(this).parents('.fUnit-item-reg').next().find('textarea').prop('disabled', false).focus().removeClass('input-disabled');
		 		//Rellenamos los select con los options correspondientes
		 		$.fillOptions();

		 	// Si se selecciona el option Matrícula con value="0" - Default	
		 	} else {
		 		//Rellenamos los select con los options correspondientes
		 		$.fillOptions();
		 		//Desactivar textarea asociado y borrar contenido
		 		$(this).parents('.fUnit-item-reg').next().find('textarea').prop('disabled', true).addClass('input-disabled').val('').parent().removeClass('input-error');
		 	}

		 });

		// Evento al pulsar boton '+ añadir matrícula'
		$('.fIU-list-item-label-btm').click(function(){
			//Comprobamos se hay mas matrículas en la unidad que select añadidos
			if (matriculas.length > $('.select-mat').size()){
				//Si todavía hay más matriculas que select, añadimos
				$('.fUnit-fNotice').clone(true).insertAfter('ol>div:first').find('textarea').prop('disabled', true).addClass('input-disabled').val('').parent().removeClass('input-warning input-error').end().end().find('select').parent().removeClass('input-error').end().end().next().removeClass('fUnit-fNotice').find('ol li label').remove().end().before('<div style="clear:both;"></div>');
			
				// Rellenamos los select con las matriculas pertinentes incluido el último añadido
				$.fillOptions();
			// En caso contrario no se añaden mas select de matrícula y alertamos
			} else {
				alert("No hay tantos vehículos asignados a la unidad");
			}
			return false;
		});

		<?php 
		// Si no hay matrículas adcritas a la unidad borramos el formulario e insertamos el siguiente parrafo
		else: ?>
			$('.fIU').empty().append('<p style="margin: 0; font-style:italic; color: #CDB380; font-size: 0.9em;">No hay vehículos asignados a esta unidad</p>');
		<?php endif; ?>

	});

</script>


<script src="js/controllers-view/modules/programming/validate/fUnit-val.js"></script>

