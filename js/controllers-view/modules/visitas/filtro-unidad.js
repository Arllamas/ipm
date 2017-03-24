	
$("#campo-unidad").on("change", function() {

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