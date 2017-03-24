<?php if($has): ?>
<ul class="select-place-results-list">
<?php while($places = mysql_fetch_assoc($result)): ?>


	<li class="select-place-results-item"><a class="select-place-results-link" data-id="<?php echo $places["IDUnidad"]; ?>" href="#"><?php echo $places["TipoUnidad"] . " " . $places["Unidad"]; ?></a></li>
<?php endwhile; ?>
</ul>

	
<script src="js/controllers-view/modules/programming/select-action.js"></script>
<?php endif; ?>