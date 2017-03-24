
	<option value="0">Elegir matrícula</option>
<?php if($has): ?>
	<?php while($mat = mysql_fetch_assoc($resultMatriculas)): ?>
		<option value="<?php echo $mat["IDMatricula"]; ?>"><?php echo $mat["Matricula"]; ?><?php if($mat["Tipo"] == "Reserva"): ?> [R]<?php endif; ?><?php if($mat["Tipo"] == "reservaProvincial"): ?> [RP]<?php endif; ?></option>
	<?php endwhile; ?>	
		<option value="all">Todos los vehículos</option>
<?php endif; ?>
