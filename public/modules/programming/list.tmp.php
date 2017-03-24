<div class="second-nav">
	<p class="second-nav-title">PROGRAMACIÓN DE ACTUACIONES</p>

	<a class="second-nav-button icon-button" href="#"><span class="second-nav-icon icon_button_i icon-eye"></span> <span class="second-nav-text icon-button-text">Importar avisos</span></a>
</div>


<div class="seccion-container">
	<div class="select-place container">
		<div class="select-place-container">
			<span class="select-place-icon icon-pin"></span>

			<!-- 
			onclick="this.setSelectionRange(0, 9999)

			Fix to select text when focus to tablets and mobile 
			--> 

			<input type="text" autocomplete="off" class="select-place-input" onclick="this.setSelectionRange(0, 9999);" name="place" id="place" placeholder="Unidad">
			
		</div>
		<div class="select-place-results"></div>

	</div>

	<div class="select-action container">
		<ul class="select-action-list">
			<label for="place"><li class="select-action-item select-action-item-unit"><a class="select-action-button unit" href="#"><span class="select-action-icon icon-unit"></span><span class="select-action-text text-unit">EN UNIDAD</span></a></li>
			<li class="select-action-item select-action-item-assistance"><a class="select-action-button assistance" href="#"><span class="select-action-icon icon-assistance"></span><span class="select-action-text">ASISTENCIA EN CARRETERA</span></a></li></label>
		</ul>
	</div>
</div>

<script src="js/controllers-view/modules/programming/select-place.js"></script>


<?php 
//Si pasamos unidad por get y ésta corresponde a una unidad de la BD, activamos acciones
if($IDUnidad): ?>
	<script>
		$( document ).ready(function() {
	 		$.activarAcciones('<?php echo $unit; ?>',<?php echo $IDUnidad; ?>);
		});
	</script>
<?php endif; ?>