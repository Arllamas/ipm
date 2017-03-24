


<?php foreach (array_reverse(glob($patron_glob)) as $key => $file): ?>
	
	<?php  $datos = get_info_report($file); ?>

	<?php
	$file = str_replace("../../../", "", $file);
	?>
	
<tr class="tableIU-row" <?php if( $key%2 != 0): ?>style="background-color: #CDB380;"<?php endif; ?> onclick="window.open('./<?php echo $file; ?>', '_blank');">
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





