
	<option value="0">Elegir repuestos</option>
<?php if($has): ?>
	<?php while($repuesto = mysql_fetch_assoc($resultado_repuestos)): ?>
		<option value="<?php echo $repuesto["IDRepuesto"]; ?>"><?php echo $repuesto["Nombre"]; ?></option>
	<?php endwhile; ?>	
<?php endif; ?>
	<option value="vacio">Nuevos repuestos</option>
