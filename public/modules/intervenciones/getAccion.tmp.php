
	<option value="0">Elegir acción</option>
<?php if($has): ?>
	<?php while($accion = mysql_fetch_assoc($resultado_acciones)): ?>
		<option value="<?php echo $accion["IDAccionElemento"]; ?>"><?php echo $accion["Accion"]; ?></option>
	<?php endwhile; ?>	
<?php endif; ?>
	<option value="vacio">Nueva acción</option>
