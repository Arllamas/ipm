<div class="second-nav">
	<p class="second-nav-title">HISTÓRICOS DE REPARACIÓN</p>
</div>

<div class="seccion-container">
	<div class="container-b fIU">
		<h3 class="fIU-list-title">DESCARGAR INFORMES MENSUALES</h3>
		<ol class="fIU-list fInformes-nav-list">
			<li class="fIU-list-item fInformes-nav-list-item">
				<label class="fIU-list-item-label" for="dt-notice" >Provincias:</label>
				<div class="fIU-list-item-container">
					<select name="" id="campo-provincia" class="fIU-list-item-select">
						<?php foreach ($provinciasMantenedora as $provincia): ?>
							<option value="<?php echo $provincia["IDProvincia"]; ?>"><?php echo $provincia["Nombre"]; ?></option>	
						<?php endforeach; ?>
					</select>
				</div>
			</li>
		</ol>
		<ol>
			<li class="fIU-list-item fInformes-body-list">
				<table class="tableIU">
					<tbody class="tableIU-body" id="body-table">
												
						<?php foreach (array_reverse(glob($patron_glob)) as $file): ?>
							<?php  $datos = get_info_report($file); ?>
						<tr class="tableIU-row" onclick="location='<?php echo $file; ?>'">
							<td class="tableIU-col"><span class="icon_excel"></span></td>
							<td class="tableIU-col">Histórico de <?php echo $datos["NombreMes"] . " " . $datos["Anno"]; ?></td>
							<td class="tableIU-col">
								<?php if($datos["Estado"] == 0): ?>
									<span class="abierto">Actualizado: <?php echo $datos["Dia"] . "-" . $datos["Mes"] . "-" . $datos["Anno"] . " " . $datos["Hora"] . ":" . $datos["Minutos"]; ?></span>
								<?php elseif($datos["Estado"] == 1): ?>
									<span class="cerrado">CERRADO</span>
								<?php endif; ?>
							</td>
						</tr>						
						<?php endforeach; ?>

					</tbody>
					
				</table>
			</li>
		</ol>
	</div>
</div>
<script>
	
$("#campo-provincia").on("change", function() {

			if($(this).find('option:selected').val()) {
				$.ajax({
					url:"public/modules/informes/getInformes.php",
					type: "POST",
					async: true,
					data: "valor=" + $(this).find('option:selected').val(),
					success: function(opciones) {
						$('#body-table').html(opciones);
					}
				});
			}
});


</script>
 