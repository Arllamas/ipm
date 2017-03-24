
	<option value="0">Elegir especificación</option>
<?php if($has): ?>
	<?php while($espec = mysql_fetch_assoc($resultado_especificacion)): ?>
		<option value="<?php echo $espec["IDEspecificacionElemento"]; ?>"><?php echo $espec["Especificacion"]; ?></option>
	<?php endwhile; ?>	
<?php endif; ?>
	<option value="vacio">Nueva especificación</option>
