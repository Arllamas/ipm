<div class="second-nav">
	<a class="second-nav-breadcrumb" href="index.php?s=p&u=<?php echo $IDUnidad; ?>"><span class="second-nav-breadcrumb-icon icon-pin"></span> <?php echo $unit; ?></a>

	<h2 class="second-nav-title"><span class="<?php if($_GET['s'] == 'p1' || $_GET['s'] == 'p2'): ?> second-nav-title-p<?php endif; ?>">PROGRAMAR ASISTENCIA EN CARRETERA</span></h2>

		
	</div>

<div class="seccion-container form">
	<!-- 
<div class="select-place container">
	<div class="select-place-container input-place">
		<span class="select-place-icon icon-pin"></span>
-->  
		<!-- 
		onclick="this.setSelectionRange(0, 9999)

		Fix to select text when focus to tablets and mobile 
		--> 
<!-- 
		<input type="text" autocomplete="off" class="select-place-input input-place-field is-active" onclick="this.setSelectionRange(0, 9999);" name="place" id="place" placeholder="Unidad" value="<?php echo $unit; ?>">
		
	</div>
	<div class="select-place-results"></div>
</div>
--> 
	<div class="fAssistance container-b fIU">
		<h3 class="fIU-list-title">REGISTRAR ASISTENCIA</h3>
		<form id="lol" action="pene">
			<ol class="fIU-list">
				<li class="fIU-list-item fAssistance-item-datetime">
					<label class="fIU-list-item-label" for="datetime" >Fecha y hora asistencia:</label>
					<div class="fIU-list-item-container">
						<input class="fIU-list-item-input" type="datetime-local" name="datetime" id="datetime" class="input-field">
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-reg">
					<label for="reg" class="fIU-list-item-label">Matrícula:</label>
					<div class="fIU-list-item-container">
						<select class="fIU-list-item-select" name="reg" id="reg">
							<option value="1">1234MMD</option>
							<option value="1">1234MMD</option>
							<option value="1">1234MMD</option>
						</select>
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-phone">
					<label for="phone" class="fIU-list-item-label">Teléfono de contacto:</label>
					<div class="fIU-list-item-container">
						<input class="fIU-list-item-input" type="tel" name="phone" id="phone" class="input-field">
					</div>
				</li>
				<li style="clear: both;"></clear>
				<li class="fIU-list-item fAssistance-item-typeroad">
					<label for="typeroad" class="fIU-list-item-label">Tipo vía:</label>
					<div class="fIU-list-item-container">
						<select class="fIU-list-item-select" name="typeroad" id="typeroad">
							<option value="1">calle</option>
							<option value="1">avenida</option>
							<option value="1">1234MMD</option>
						</select>
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-nameroad">
					<label class="fIU-list-item-label" for="nameroad">Nombre vía:</label>
					<div class="fIU-list-item-container">
						<input class="fIU-list-item-input" type="text" name="nameroad" id="nameroad" class="input-field">
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-numberroad">
					<label class="fIU-list-item-label" for="numberroad">Número:</label>
					<div class="fIU-list-item-container">
						<input class="fIU-list-item-input" type="text" name="numberroad" id="numberroad" class="input-field">
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-detalle">
					<label class="fIU-list-item-label" for="detaillocation">Detalle ayuda localización (Esquina, cercania, etc)</label>
					<div class="fIU-list-item-container">
						<input class="fIU-list-item-input" type="text         " name="detaillocation" id="detaillocation" class="input-field">
					</div>
				</li>
				<li class="fIU-list-item fAssistance-item-descripcion">
					<label class="fIU-list-item-label" for="descriptionproblem">Descripción de la avería:</label>
					<div class="fIU-list-item-container">
						<textarea class="fIU-list-item-textarea" type="descriptionproblem" name="descriptionproblem" id="descriptionproblem" class="input-field"></textarea>
					</div>
				</li>
			</ol>
				<input type="button" class="fIU-list-item-btm" id="btnw" value="Grabar asistencia" />
		</form>
	</div>
</div>
	
<div id="dialog" title="Dialogo básico">
			
		</div>
	<script>
		$(function(){
			$('#dialog').dialog({
				modal: true,
				autoOpen: false
			});
		});

		$('#btnw').click(function(){
			$('#dialog').dialog('option', 'width', '80%');
			$('#dialog').dialog('option', 'height', 'auto');
		
			$('#dialog').dialog('open');
		});
	</script>
<script src="js/controllers-view/modules/programming/select-place.js"></script>
