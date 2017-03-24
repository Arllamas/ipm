;(function($, window, undefined) {


// Interacción de campos 
// Mostrar y ocultar opciones según se vaya rellenando el formulario

$.fn.controlFields = function(type, status, val, field) {

	switch(type) {

			// Aplicar acciones al elemento $(this) directamnete
			case 'element':

				// Activar y no tocar valores
				if(status == 'on' && val == 0) {
					return $(this).removeAttr("disabled").removeClass('input-disabled');
					break;
				
				// Activar y dar valor 
				} else if(status == 'on' && val == 1) {
					return $(this).removeAttr('disabled').prop('checked', true).next().removeClass('input-disabled');

				// Desactivar y no tocar valores	
				} else if(status == 'off' && val == 0) {
					if($(this).is("input:checkbox") || $(this).is("input:radio")){	
						return $(this).attr('disabled','disabled').next().addClass('input-disabled');
					} else {
						return $(this).attr('disabled','disabled').addClass('input-disabled');
					}
					
					break;	

				// Desactivar y eliminar valor 
				} else if(status == 'off' && val == -1) {

					// Si es checkbox o radio hay que aplicarle estilo desactivado también al elemento hermano label
					if($(this).is("input:checkbox") || $(this).is("input:radio")){	
						return $(this).attr('disabled','disabled').prop('checked', false).next().addClass('input-disabled');
					} else {
						return $(this).attr('disabled','disabled').addClass('input-disabled').val('');
					}
					
					break;	
				}
					
			// Aplicar acciones a los elementos que contiene el contenedor $(this)
		    case 'container':

		    		// Activar elementos y no tocar valores
 		    		if(status == 'on' && val == 0) {
		    			$(this).find('input:radio, input:checkbox').removeAttr("disabled").next().removeClass('input-disabled');
		    		
		    		// Desactivar elementos y no tocar valores
		    		} else if (status == 'off' && val == 0) {

		    			$(this).find('input:radio, input:checkbox').attr('disabled','disabled').next().addClass('input-disabled');

					// Desactivar elementos y borrar valores

		    		} else if (status == 'off' && val == -1) {
		    			$(this).find('input:radio, input:checkbox').attr('disabled','disabled').prop('checked', false).next().addClass('input-disabled');
		    		}
					
		        break;    
		}	

}

		// Evento al seleccionar cualquier select
		// ELEMENTO | ESPECIFICACION | ACCION | REPUESTO
		$(".fDetalleInterv-list select").on("change", function() {

			// Al seleccionar 'Nuevo ELEMENTO | ESPECIFICACION | ACCION | REPUESTO'
			if($(this).find('option:selected').val() == 'vacio') {
				var name_select = $(this).attr('name');
				$(this).blur().hide().parent().append('<input type="text" name="' + name_select + '[0]" style="padding-right: 30px;" class="fIU-list-item-input input-nuevo" /><button onclick="return false;"class="detalle_btn_back"><span class="icon_back"></span></button>').find('.fIU-list-item-input').focus();



				$(this).attr('disabled', 'disabled');


			}

			// Al hacer click en 'el boton de volver al select de ACCION'
			$('.campo-accion').parents('.fDetalleInterv-list-item').find('.detalle_btn_back').click(function(){ 
				$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo, button').remove().end().find('select').show().controlFields('element', 'off', 0).find('option[value=0]').prop('selected', true);
			});

			$('.detalle_btn_back').click(function(){

				var that = $(this).parent().children('select');
				$(this).parent().find('.fIU-list-item-input').remove();
				$(this).remove();
				$(that).attr('disabled', false).find('option[value="0"]').prop('selected', true).end().find('option[value="vacio"]').prop('selected', false).end().show();
			});
		});
		
		
		// Evento al seleccionar Elemento 
		 $(".campo-elemento").on("change", function() {

		 	// Si se selecciona un elemento
			if($(this).find('option:selected').val() != 0 && $(this).find('option:selected').val() != 'vacio') {
				
				// Si estuviesen los input de 'nuevo valor' los quitamos y mostramos los select 
				$(this).parents('.fDetalleInterv-list').find('input.input-nuevo, button').remove();
				// Mostramos los select
				$(this).parents('.fDetalleInterv-list').find('select').show();
				$(this).parents('.fDetalleInterv-list').find('input.alternativo').attr('disabled', true);

				
				var that = $(this);
				$.ajax({
					url:"public/modules/intervenciones/getEspecificacion.php",
					type: "POST",
					async: true,
					data: "valor=" + $(this).find('option:selected').val(),
					success: function(opciones) {

						$(that).parents('li.fDetalleInterv-list-item').next().find('select').html(opciones);
					}
				});

				var that = $(this);
				$.ajax({
					url:"public/modules/intervenciones/getAccion.php",
					type: "POST",
					async: true,
					data: "valor=" + $(this).find('option:selected').val(),
					success: function(opciones) {

						$(that).parents('li.fDetalleInterv-list-item').next().next().find('select').html(opciones);
					}
				});

				var that = $(this);
				$.ajax({
					url:"public/modules/intervenciones/getRepuestos.php",
					type: "POST",
					async: true,
					data: "valor=" + $(this).find('option:selected').val(),
					success: function(opciones) {

						$(that).parents('li.fDetalleInterv-list-item').next().next().next().find('select').html(opciones);
					}
				});

				$(this).parents('.fDetalleInterv-list').find('select').not(':first').controlFields('element', 'off', 0);
				$(this).parents('.fDetalleInterv-list').find('select').not(':last').controlFields('element', 'on', 0);
			// Al seleccionar 'Elegir elemento'
			} else if($(this).find('option:selected').val() == 0) {
				$(this).parents('.fDetalleInterv-list').find('input.alternativo').attr('disabled', false);
				$(this).parents('.fDetalleInterv-list').find('input.input-nuevo, button').remove();
				$(this).parents('.fDetalleInterv-list').find('select').not(':first').show().controlFields('element', 'off', 0).classToContainer('input-warning', 'r').find('option[value="0"]').prop('selected', true);
			// Al seleccionar 'Nuevo elemento'
			} else if($(this).find('option:selected').val() == 'vacio') {

				$(this).parents('.fDetalleInterv-list').find('select').not(':first').controlFields('element', 'off', 0);

				// Convierte el resto de select en input sin boton de volver
				$(this).parents('.fDetalleInterv-list-item').siblings().find('select').hide().end().find('.fIU-list-item-container').find('input.input-nuevo').remove().end().find('button').remove().end().append('<input style="padding-right: 30px;"class="fIU-list-item-input input-nuevo input-disabled" />').find('input.input-nuevo').controlFields('element', 'off', 0).classToContainer('input-warning', 'r');

				$(this).parents('.fDetalleInterv-list-item').find('.input-nuevo').attr('name', $(this).attr('name') + "[]").parents('.fDetalleInterv-list-item').find('input.alternativo').attr('disabled', true);
				$(this).parents('.fDetalleInterv-list-item').next().find('.input-nuevo').attr('name', $(this).parents('.fDetalleInterv-list-item').next().find('select').attr('name') + "[]").parents('.fDetalleInterv-list-item').find('input.alternativo').attr('disabled', false);
				$(this).parents('.fDetalleInterv-list-item').next().next().find('.input-nuevo').attr('name', $(this).parents('.fDetalleInterv-list-item').next().next().find('select').attr('name') + "[]").parents('.fDetalleInterv-list-item').find('input.alternativo').attr('disabled', false);
				$(this).parents('.fDetalleInterv-list-item').next().next().next().find('.input-nuevo').attr('name', $(this).parents('.fDetalleInterv-list-item').next().next().next().find('select').attr('name') + "[]").parents('.fDetalleInterv-list-item').find('input.alternativo').attr('disabled', false);
				

				var that = $(this);

				// Al pulsar click en boton para volver al selec de elementos
				$(this).parents('.fDetalleInterv-list-item').find('button').click(function(){
					

					$(that).parents('.fDetalleInterv-list-item').siblings().find('input.input-nuevo').remove().end().find('select').find('option[value="0"]').prop('selected', true).end().controlFields('element','off', 0).show().classToContainer('input-warning', 'r');

				});

				// Al escribir nuevo elemento
				$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').on('keyup', function(){
					// Si hay algo escrito en el input
					if($(this).val() != "") {
						
						$(this).parents('.fDetalleInterv-list').find('input.input-nuevo').not(':first, :last').controlFields('element', 'on', 0);
						$(this).parents('.fDetalleInterv-list').find('input.input-nuevo').not(':first, :last').find('option[value!=vacio]').not("option[value=0]").remove();
					// Si no hay algo escrito en el input
					} else {
						
						$(this).parents('.fDetalleInterv-list').find('input.input-nuevo').not(':first').controlFields('element', 'off', -1).classToContainer('input-warning', 'r');
						$(this).parents('.fDetalleInterv-list').find('input.alternativo').attr('disabled', false)
					}
				});


				$(this).parents('.fDetalleInterv-list').find('input.input-nuevo').not(':first, :last').controlFields('element', 'off', 0);

				$(this).parents('.fDetalleInterv-list').find('select.campo-accion').parent().find('input.input-nuevo').on('keyup', function(){
					// Si hay algo escrito en el input
					if($(this).val() != "") {
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').controlFields('element', 'on', 0);
						$(this).parents('.fDetalleInterv-list').find('input.alternativo').attr('disabled', true)
					// Si no hay algo escrito en el input
					} else {

						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').controlFields('element', 'off', -1);
					}

				});

			}
		});


		// Evento al seleccionar accion

		$(".campo-accion").on("change", function() {

			// Al seleccionar una acción de la lista
			if($(this).find('option:selected').val() != 0 && $(this).find('option:selected').val() != 'vacio') {
				// Mostrar select de repuestos
				$(this).parents('.fDetalleInterv-list-item').next().find('select').controlFields('element', 'on', 0);	
			
			// Al seleccionar 'Elegir acción'
			} else if ($(this).find('option:selected').val() == 0) {
				// Seleccinar opcion 'Elegir repuesto'
				$(this).parents('.fDetalleInterv-list-item').next().find('select option[value=0]').prop('selected', 'selected');
				// Si está activo el input de nuevo repuesto lo borramos y mostramos el select de repuestos oculto
				$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo, button').remove().end().find('select').controlFields('element', 'off', 0).show();

			// Al seleccionar 'Nueva acción'
			} else if ($(this).find('option:selected').val() == 'vacio') {
				// Seleccinar opcion 'Elegir repuesto'
				$(this).parents('.fDetalleInterv-list-item').next().find('select option[value=0]').prop('selected', 'selected');
				// Ocultar select de repuestos
				$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo, button').remove().end().find('select').controlFields('element', 'off', 0).show();
				var that = $(this);

				$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').on('keyup', function(){
					// Al escribir nuevo elemento
					// Si hay algo escrito en el input
					if($(this).val() != "") {
						// Mostrar select de repuestos
						$(that).parents('.fDetalleInterv-list-item').next().find('select').controlFields('element', 'on', 0);
						$(that).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').controlFields('element', 'on', 0);
					// Si no hay algo escrito en el input
					} else {
						// ocultar select de repuestos
						$(that).parents('.fDetalleInterv-list-item').next().find('select').controlFields('element', 'off', 0);
						$(that).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').controlFields('element', 'off', -1);
					}
				});
			}
			
		});

		// Evento al pulsar boton '+ Nuevo detalle'

		$('#btn_detalle').click(function(){
					
					$("*").classToContainer('input-warning', 'r'); // Oculto todos los warnings  que haya
					$("*").classToContainer('input-error', 'r');

					$('body > div.alert').remove(); // Oculto alertas si las hay

					validador_detalle = $.validar_detalle(true);

					

					if(validador_detalle['linea_incompleta'] == false) {
						$('ol.fDetalleInterv-list:first').clone(true).insertAfter('ol.fDetalleInterv-list:last').find('label').addClass('label-variable').end().find('input.input-nuevo, button').remove().end().find('select').find('option[value=0]').prop('selected', true).end().parents('.fDetalleInterv-list').find('input.alternativo').attr('disabled', false).find('option[value="vacio"]').prop('selected', false).end().end().find('select').controlFields('element', 'off', 0).filter(':first').controlFields('element', 'on', 0).parents('.fDetalleInterv-list').find('select').show();
					}		

					





					// Lanzar alertas de '+ Nuevo detalle'

					// Crear la caja de alertas
					$('body').append('<div class="alert">');
					$('.alert').append('<h3 style="margin: 0; margin-bottom: 5px; font-size: .9em; text-decoration: underline;">HAY LÍNEAS DE DETALLE DISPONIBLE</h3>');
					$('.alert').hide(); // Ocultar de momento

					var primera_linea = new Array();
					primera_linea['posicion'] = 0;
					primera_linea['tipo'] = false;
					var control_errores = false;

					$.each(validador_detalle['errores_detalle'], function(i,v){
						

						if(validador_detalle['errores_detalle'][i]['posicion']){
							control_errores = true;
								
							if(validador_detalle['errores_detalle'][i]['posicion'] > primera_linea['posicion'] && primera_linea['posicion'] == 0) {


								primera_linea['posicion'] = validador_detalle['errores_detalle'][i]['posicion'];
								primera_linea['tipo'] = validador_detalle['errores_detalle'][i]['tipo'];
							}							

							$('.alert').append('<li class="alert-list-item">Detalle ' + validador_detalle['errores_detalle'][i]['posicion'] + ': '+ validador_detalle['errores_detalle'][i]['msg'] +'</li>');	
						}
						
					});
					

					if(control_errores) {
						$('.alert').addClass('warning');

						// Mostrar y ocultar al rato las alertas

					$(window).resize(function () {
						if ($(window).width() > 485) {
							$(".alert").css({
									zIndex: 3,
									position: 'fixed',
									// Se alinea en la horizontal cogiendo medidas del navegador 
									left: ($(window).width() - $('.alert').outerWidth()) / 2,
									top: ($(window).height() - $('.alert').outerHeight() - 20)

							});
						} else {
							$(".alert").css({
									zIndex: 3,
									position: 'fixed',
									// Se alinea en la horizontal cogiendo medidas del navegador 
									left: ($(window).width() - $('.alert').outerWidth()) / 2,
									top: (20)

							});
						}
					});

						// El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
						// Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
						$(".alert").fadeIn(500, function(){
							$('.alert').delay(5000 * $('.alert-list-item').size())
							.fadeOut(500);
						});	

						$('html,body').animate({scrollTop: $(primera_linea['tipo']).offset().top - 150}, 1000);
						// Ejecutar la función que muestra y oculta las alertas
						$(window).resize();	

					}
					
					return false;
		});


// Evento al seleccionar una unidad 
		 $("#campo-unidad").on("change", function() {

		 	// Si se selecciona una unidad
			if($(this).find('option:selected').val() != 0) {
				$.ajax({
					url:"public/modules/intervenciones/getMatriculas.php",
					type: "POST",
					async: true,
					data: "valor=" + $(this).find('option:selected').val(),
					success: function(opciones) {
						// Activar campo matrícula
						// Rellenar los options con las matrículas
						$('#campo-matricula').controlFields('element', 'on', 0).html(opciones);
						$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
						$('.container-fIntervenciones').css("text-align", "justify");
 						$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').removeClass('todos');
					}
				})

		 	// Si se selecciona la opción 'Elegir unidad' del select de Unidad
		 	} else {
		 		
		 		$('.container-fIntervenciones').css("text-align", "justify");
 				$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').removeClass('todos');
			 	$('#campo-matricula').classToContainer('input-warning', 'r');
				$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
		 		// Vaciamos y desactivamos campo de matrículas
		 		$('#campo-matricula').controlFields('element','off', 0).html('<option value="0">Elige matrícula</option>');


		 	}

		 	// En cualquier caso
		 	// Desactivar y borrar cuenta KM
		 	$('#c-km').controlFields('element', 'off', -1);

		 	$('.fDetalleInterv-list select').classToContainer('input-warning','r');
		 	$('.fDetalleInterv-list').show();

	 		// Desactivar y borrar todas las opciones del resto de bloques
	 		$('.fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-tipo-list, .fIntervenciones-desplazamiento-list').controlFields('container', 'off', -1);
	 		$("#campo-des-unidades").controlFields('element', 'off', 0).val(0);
	 		$('#tipo-averia, #tipo-asistencia, #tipo-unidad, #tipo-desplazamiento, #tipo-siniestro, #lugar-calle, #lugar-taller, #preven-itv, #preven-neumaticos, #preven-frenos, #preven-aceite').parent().show();
 			// Mostrar si estuviese mostrado el bloque de correctivos
 			$('.fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list').show();

 			$('.fDetalleInterv-list').parent().children('label').show();

 			// Resetear bloque de 'Detalle de reparaciones'
 			$('.fDetalleInterv-list').not(':first').remove().end().find('input.input-nuevo, button').remove().end().find('select').controlFields('element', 'off', 0).show().find('option[value=0]').prop('selected', true);

 			$('.fIntervenciones-btm_detalle').hide();

 			$('.fIntervenciones-desplazamiento-list').addClass('hidden');
 		
 			if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
				$('.fIntervenciones-multiselect').addClass('hidden');
 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);
 				

 				
					
	 		} else {
	 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
	 			$(':checkbox').removeAttr('checked');
	 			$('input[aria-selected=true]').removeAttr('aria-selected');
	 			$('.fIntervenciones-multiselect').addClass('hidden');
 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);
	 		}

		 });

		
		// Al cambiar matrícula
		$("#campo-matricula").on("change", function() {
			
			// Al seleccionar opcion 'Todos los vehiculos' solo permite grabar actuaciones preventivas
 			if($(this).find('option:selected').val() == 'all') {

 				$('.fDetalleInterv-list select').classToContainer('input-warning','r');
 				$('.container-fIntervenciones').css("text-align", "left");
 				$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').addClass('todos');

 				$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'r');
				$('.fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'r');
				
				$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');

 				// Mostrar todos los bloques menos correctivos
 				$('.fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list').not('.otras').show();
 				
 				// Ocultar si estuviese mostrado la segunda columna del bloque de preventivos
 				$('.fIntervenciones-preventivas-list.otras').hide();

 				//Ocultar el label de 'Detalle de reparaciones'
 				$('.fIntervenciones-btm_detalle, .fDetalleInterv-list').hide();
 				$('.fDetalleInterv-list').parent().children('label').hide();


 				// Ocultar bloque desplazamiento,
 				$('.fIntervenciones-desplazamiento-list').addClass('hidden');
 				// Ocultar opciones sobrantes
 				$('#tipo-averia, #tipo-asistencia, #tipo-unidad, #tipo-desplazamiento, #tipo-siniestro, #lugar-calle, #lugar-taller, #preven-itv, #preven-neumaticos, #preven-frenos, #preven-aceite').parent().hide();


 				// Desactivar y borrar cuenta KM
		 		$('#c-km').controlFields('element', 'off', -1);
 				// Ocultamos todas las opcións de tipo previamente y las desmarcamos
 				$('.fIntervenciones-tipo-list').controlFields('container', 'off', -1);
 				// Se habilita la opción de tipo 'Act. preventivas' marcada
 				$('#tipo-preventivas').controlFields('element', 'on', 1);
 				// Se desactiva el bloque ' Reparada En' y ' Actuaciones correctivas' 
 				$('.fIntervenciones-correctivos-list, .fIntervenciones-lugarReparacion-list').controlFields('container', 'off', 0);
 				// Activar y marcar Lugar de actuacion "En unidad" 
 				$('#lugar-unidad').controlFields('element', 'on', 1);
 				// Se habilita el bloque de actuaciones preventivas
 				$('.fIntervenciones-preventivas-list').controlFields('container', 'on', 0);
 				// Desactivar opciones de actuaciones preventivas
 				$('#preven-itv').controlFields('element', 'off', 0);
 				$('#preven-neumaticos').controlFields('element', 'off', 0);
 				$('#preven-frenos').controlFields('element', 'off', 0);
 				$('#preven-aceite').controlFields('element', 'off', 0);

 			// Al seleccionar Elige matrícula oculta lectura c-km y todos los paneles menos el general
 			} else if($(this).find('option:selected').val() == 0) {

 				$('.fDetalleInterv-list select').classToContainer('input-warning','r');

 				$('.container-fIntervenciones').css("text-align", "justify");
 				$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').removeClass('todos');
				
				$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'r');
				$('.fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'r');
				$('.fIntervenciones-correctivos-list').controlValidateOptions('w', 'r');	
				$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');

 				// Desactivar y borrar todas las opciones del resto de bloques
	 		$('.fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-tipo-list, .fIntervenciones-desplazamiento-list').controlFields('container', 'off', -1);
	 		$("#campo-des-unidades").controlFields('element', 'off', 0).val(0);
	 		$('#tipo-averia, #tipo-asistencia, #tipo-unidad, #tipo-desplazamiento, #tipo-siniestro, #lugar-calle, #lugar-taller, #preven-itv, #preven-neumaticos, #preven-frenos, #preven-aceite').parent().show();
 			// Mostrar si estuviese mostrado el bloque de correctivos
 			$('.fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list, .fDetalleInterv-list').show();
 			$('.fDetalleInterv-list').parent().children('label').show();

 			// Resetear bloque de 'Detalle de reparaciones'
 			$('.fDetalleInterv-list').not(':first').remove().end().find('input.input-nuevo, button').remove().end().find('select').controlFields('element', 'off', 0).show().find('option[value=0]').prop('selected', true);

 			$('.fIntervenciones-btm_detalle').hide();


 			$('.fIntervenciones-desplazamiento-list').addClass('hidden');
 				if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
				$('.fIntervenciones-multiselect').addClass('hidden');
 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);

					
		 		} else {
		 				$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
		 			$(':checkbox').removeAttr('checked');
		 			$('input[aria-selected=true]').removeAttr('aria-selected');
		 			$('.fIntervenciones-multiselect').addClass('hidden');
	 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
	 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);

		 		}

 				// Desactivar y borrar cuenta KM
		 		$('#c-km').controlFields('element', 'off', -1);

		 		// Desactivar y borrar todas las opciones del resto de bloques
		 		$('.fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-tipo-list, .fIntervenciones-desplazamiento-list').controlFields('container', 'off', -1);

		 	// Al seleccionar cualquier matrícula
 			} else {

 				if(!$("#tipo-desplazamiento").is(':checked')) {
 					$('.container-fIntervenciones').css("text-align", "justify");
 					$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').removeClass('todos');
 				}
 				
 				// Ocultar todas las opciones
 				$('#tipo-averia, #tipo-asistencia, #tipo-unidad, #tipo-desplazamiento, #tipo-siniestro, #lugar-calle, #lugar-taller, #preven-itv, #preven-neumaticos, #preven-frenos, #preven-aceite').parent().show();

 				// Mostrar si estuviese ocultada la segunda columna del bloque de preventivos
 				$('.fIntervenciones-preventivas-list.otras').show();


 				// Si está la opción de tipo 'Act. preventivas'
 				if($('#tipo-preventivas').is(':checked')) {
 					// Si hay activada alguna opción del bloque 'Reparada En'
 					if($('.fIntervenciones-lugarReparacion-list').hasActiveOption()){
 						//Activar bloques
 						$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list, .fIntervenciones-correctivos-list').controlFields('container', 'on', 0);


 						$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
 						// Activar bloque detalle de reparacion

 						$('.fDetalleInterv-list').show().find('.campo-elemento').controlFields('element', 'on', 0);
 						$('.fDetalleInterv-list').parent().children('label').show();
 						$('.fIntervenciones-btm_detalle').show();

 					} else {
 						//Desaactivar bloques
 						$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list, .fIntervenciones-correctivos-list').controlFields('container', 'off', 0);
 					}
 				}

 					
 					
 				// Activar bloque 'Tipo de parte'
				// Si está seleccionada una unidad activar todos menos sustitución
			
				if($("#campo-unidad").find('option:selected').val()) {
					
					// Si hay activada alguna opción del bloque 'Reparada en'
					if($('.fIntervenciones-lugarReparacion-list').hasActiveOption()){
	 					// Activar bloques
		 				$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list, .fIntervenciones-correctivos-list').controlFields('container', 'on', 0);
	 				} else {
	 					// Desactivar bloques
 						$('.fIntervenciones-correctivos-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-tipo-list, .fIntervenciones-desplazamiento-list').controlFields('container', 'off', 0);
	 				}
	 				// Activar c-km
					$('#c-km').controlFields('element', 'on', 0);

 					// Mostrar bloque tipo
 					$('.fIntervenciones-tipo-list').controlFields('container', 'on', 0);
 					// Desactivar y desmarcar opción sustitución
					// $('#tipo-desplazamiento').controlFields('element', 'off', -1);
				// Si está seleccionada la 'Reserva provicial' activar todas las opciones	
				} 
 			}

 			if($('#tipo-desplazamiento').is(':checked')) {
 				
    			$('.fIntervenciones-correctivos-list').hide();
    			$('.fIntervenciones-desplazamiento-list').controlFields('container', 'on', 0);
			}

			if($('#tipo-averia, #tipo-asistencia, #tipo-unidad, #tipo-preventivas').is(':checked')) {
 					$('.fIntervenciones-lugarReparacion-list').controlFields('container', 'on', 0);
 				}
 		});
		
		// Al seleccionar una opción del bloque tipo de parte
 		$('.fIntervenciones-tipo-list').find('input:radio').on("change", function() {
 			// Si se selecciona 'Desplazamiento'
 			if($(this).filter('#tipo-desplazamiento').is(':checked')) {
 				$('.container-fIntervenciones').css("text-align", "left");
 				
 				
				$('.fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'r');
				
				$('.fDetalleInterv-list select').classToContainer('input-warning', 'r');
				$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');

 				// Desactivar c-km
				$('#c-km').controlFields('element', 'on', 0);
				
				
				// Activar opciones de desplazamiento
				$('.fIntervenciones-desplazamiento-list').controlFields('container', 'on', 0);
				// Ocultar todos los bloques
 				$('.fIntervenciones-preventivas-list, .fIntervenciones-correctivos-list, .fIntervenciones-lugarReparacion-list, .fDetalleInterv-list').hide();
 				//Ocultar el label de 'Detalle de reparaciones'
 				$('.fIntervenciones-btm_detalle').hide();
 				$('.fDetalleInterv-list').parent().children('label').hide();

 				// Mostrar bloque desplazamiento
 				$('.fIntervenciones-desplazamiento-list').removeClass('hidden');

 				if($('#despla-recogida, #despla-entregada').is(':checked')) {
    				$('#campo-des-unidades').controlFields('element', 'on', 0);
				}
			
 			// Si se selecciona 'Cualquier otra opción'
 			// 'Aviso de avería', 'Asistencia', 'Aviso en unidad', 'Act. preventivas'
 			} else {
 				$('.container-fIntervenciones').css("text-align", "justify");
 				// Ocultar todos los bloques
 				$('.fIntervenciones-preventivas-list, .fIntervenciones-lugarReparacion-list').show();
 				// Mostrar bloque desplazamiento
 				$('.fIntervenciones-desplazamiento-list').addClass('hidden');

 				// Si no hay opciones seleccionadas en el bloque lugar de reparacion
 				if(!$('.fIntervenciones-lugarReparacion-list').hasActiveOption()){

 					// Ocultar si estuviese mostrado el boton de 'Nuevo Detalle'
 					$('.fIntervenciones-btm_detalle').hide();

 					// Desactivar bloques preventivos y correctivos	
	 				$('.fIntervenciones-preventivas-list, .fIntervenciones-correctivos-list').controlFields('container', 'off', 0);
	 				// Activar bloque lugar reparación
	 				$('.fIntervenciones-lugarReparacion-list').controlFields('container', 'on', 0);
	 				// Activar c-km
	 				$('#c-km').controlFields('element', 'on', 0);	
 				// Si hay opciones seleccionadas en el bloque lugar de reparacion
 				} else {
 					// Mostrar el boton de 'Nuevo Detalle'
 					$('.fIntervenciones-btm_detalle').show();
 					// Activar bloque lugar reparación
	 				$('.fIntervenciones-lugarReparacion-list, .fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list').controlFields('container', 'on', 0);
	 				// Activar c-km
	 				$('#c-km').controlFields('element', 'on', 0);
 				}

 				//Ocultar el label y el bloque completo de 'Detalle de reparaciones' 
 				$('.fDetalleInterv-list').show();
 				$('.fDetalleInterv-list').parent().children('label').show();
 			}

 		});

		$('.fIntervenciones-desplazamiento-list').find('input:radio').on("change", function() {
			$('#campo-des-unidades').controlFields('element', 'on', 0);

			if($('#despla-recogida, #despla-entregada').is(':checked')) {
    				$('#campo-des-unidades').controlFields('element', 'on', 0);
				}

		});

 		// Al seleccionar una opción del bloque lugar de reparacion
 		$('.fIntervenciones-lugarReparacion-list').find('input:radio').on("change", function() {
			
 			// Activar los bloques de preventivo y correctivo
 			$('.fIntervenciones-preventivas-list').controlFields('container', 'on', 0);
 			$('.campo-elemento').controlFields('element', 'on', 0);
 			$('.fIntervenciones-btm_detalle').removeClass('hidden').show();

 		});


 		$('.fIntervenciones-correctivos-list').find('input:checkbox').on("change", function() { 

 			if($(this).is(':checked')){
 				
 				if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
					$(this).next().next().removeClass('hidden').next().removeClass('hidden');
	 				
		 		} else {
		 			$(this).next().next().next().removeClass('hidden').next().removeClass('hidden');
		 		}
 			} else {
 				
 				if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
					$(this).next().next().addClass('hidden').next().addClass('hidden');
 					
		 		} else {
		 			$(this).next().next().next().addClass('hidden').next().addClass('hidden');
		 		}
 			}
 			
 		});




// Evento al pulsar boton 'Grabar actuaciones' del form
$('#btnw').click(function(){

	$.hay_errores_detalle();

	// Metodo para encontrar elementos desde un elemento padre
	// Parametro next opcional para buscar dentro del siguiente elemento hermano al padre
	$.fn.findFromParent = function(parent, element, next) {

			return (next) ?
				$(this).parents(parent).next().find(element)
			:
				$(this).parents(parent).find(element)
			;

	}

	// Función que a partir de una fecha que se pasa como parametro obtiene fecha formateada
	// Parametro en formato que obtenemos de campo datetime-local (AAAA/MM/DDTHH:MM:SS)
	$.formatDate = function(date, type) {

		// Array para hacer la traducción de mes numérico
		var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

		// Función que obtiene de una fecha con formato DD/MM/AÑO el día de la semana
		function dia_semana(f){   
			    f = f.split('/');  
			    
			    if(f.length!=3){  
			        return null;  
			    }  
			    // Array para calcular día de la semana de un año regular.  
			    var regular =[0,3,3,6,1,4,6,2,5,0,3,5];   
			    // Array para calcular día de la semana de un año bisiesto.  
			    var bisiesto=[0,3,4,0,2,5,0,3,6,1,4,6];   
			    // Array para hacer la traducción de resultado en día de la semana.  
			    var semana=['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];  
			    //Día especificado en la fecha recibida por parametro.  
			    var d=f[0];  
			    //Módulo acumulado del mes especificado en la fecha recibida por parametro.  
			    var m=f[1]-1;  
			    //Año especificado por la fecha recibida por parametros.  
			    var a=f[2];  
			    //Comparación para saber si el año recibido es bisiesto.  
			    if((a % 4 == 0) && !(a % 100 == 0 && a % 400 != 0)) {
			        m=bisiesto[m];  
			    } else  {
			        m=regular[m];  
			    }
			    //Se retorna el resultado del calculo del día de la semana.  
			    return semana[Math.ceil(Math.ceil(Math.ceil((a-1)%7)+Math.ceil((Math.floor((a-1)/4)-Math.floor((3*(Math.floor((a-1)/100)+1))/4))%7)+m+d%7)%7)];  
			}  

		// Quitar segundos (3 últimos caracteres :00) y preparar para split 
		var fechaHoraAviso = date.substring(0,date.length - 3).replace('T',' ');

		// Separar la fecha en partes
		var fechaHora = fechaHoraAviso.split(' ');
			var fecha = fechaHora[0];
				var separarFecha = fecha.split('-');
					var dia = separarFecha[2];
					var mes = meses[separarFecha[1]-1];
					var anno = separarFecha[0];
					var diaSemana = dia_semana(dia + '/'+ separarFecha[1] + '/' + anno);
			var hora = fechaHora[1];
		

		

		if(type == 1) {
			// TIPO 1: 14/03/2015 HH:MM
			// Componer la fecha en el formato deseado	
			var fechaFormateada = dia + "/" + separarFecha[1] + "/" + anno + " " + hora; 
		} else if(type == 2) {
			// TIPO 2: Lunes 23 de Febrero de 2015
			// Componer la fecha en el formato deseado	
			var fechaFormateada = diaSemana + " " + dia + " de " + mes + " del " + anno; 	
		}

		
		

		return fechaFormateada;
	}

	// Función que muestra la ventana modal con los datos rellenados en el formulario
	// Pasar como parametro array completed
	$.showModal = function(arrayCompleted) {

		// Crear ventana modal con el resumen para confirmación del formulario
		// Vaciar (empty) ventana modal cada vez que se muestra
		$('body').append('<div id="dialog" title="Resumen de intervención">').find('#dialog').empty();

		

	      		
		// Configurar ventana modal
		$('#dialog').dialog({
			modal: true,
			draggable: false,
			autoOpen: false,
			resizable: false,
			width: '80%',
			height: 'auto',
			maxHeight: $(window).height() * 0.7,
			closeOnEscape: true,
			buttons: [
				{
					text: "Confirmar",
			        class: 'dialog-confirm-btm', 
			        click: function() { 
			        	if(completed['prevenAll']) {
				        	// Validación campos del showmodal 
				        	var lavprevShow
				        	$.each($('.container-lav-check, .container-prev-check'),function(i,v) {
								if($(v).is(':checked')) {
								$(v).next('input[type=hidden]').next().attr('disabled', true);
								lavprevShow = true;
								} else {
								$(v).next('input[type=hidden]').attr('disabled', false);
								}

							});

							if(!lavprevShow) {
								$(this).dialog("close");
								$('#preven-completo').classToContainer('input-warning', 'a').end().prop('checked', false);
								$('#preven-lavado').classToContainer('input-warning', 'a').end().prop('checked', false);
								return false;
							}
						}	

			            // Volver a permitir scroll en body
			            $(this).dialog("close");
			            $(this).appendTo('form').hide();
			            $('body').css('overflow','auto');
						$('body').css('position','static');
						// Procesar formulario en servidor

						// Desactivos campos ocultos o inactivos antes de enviar formulario
						if(completed['prevenAll']){
							
							// Desactivar opciones de desplazamiento
							$(".fDetalleInterv-list").remove();
							$("#campo-des-unidades option:selected").prop("selected", false).parent().remove();
							$('#despla-entrega').prop('checked', false)
							$('#despla-recogida').prop('checked', false)

							// Borrar informacion de repuestos
							if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
								$('.fIntervenciones-multiselect').addClass('hidden');
				 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
				 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);
				 				

				 				
									
					 		} else {
					 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
					 			
					 			$('input[aria-selected=true]').removeAttr('aria-selected');
					 			$('.fIntervenciones-multiselect').addClass('hidden');
				 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
				 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);
					 		}

					 		// Desactivar todas las actuaciones preventivas menos lavados y M.preventiva
					 		$('.fIntervenciones-preventivas-list').find('input[id!=preven-completo]').not('input[id=preven-lavado]').prop('checked', false)

						} else if(completed['correcPreven']){

							// Desactivar opciones de desplazamiento
							$("#campo-des-unidades option:selected").prop("selected", false).parent().remove();
							$('#despla-entrega').prop('checked', false)
							$('#despla-recogida').prop('checked', false)


							if(!$('#correc-mecanica').is(':checked')) {
								// Borrar informacion de repuestos
								if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
									$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-mecanica option').prop('selected',false);
					 				

					 				
										
						 		} else {
						 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
						 			
						 			$('input[aria-selected=true]').removeAttr('aria-selected');
						 			$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-mecanica option').prop('selected',false);
						 		}
							}
							if(!$('#correc-electrica').is(':checked')) {
								if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
									$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-electrica option').prop('selected',false);
					 				

					 				
										
						 		} else {
						 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
						 			
						 			$('input[aria-selected=true]').removeAttr('aria-selected');
						 			$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-electrica option').prop('selected',false);
						 		}
							}
							if(!$('#correc-neumaticos').is(':checked')) {
								if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
									$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-ruedas option').prop('selected',false);
					 				

					 				
										
						 		} else {
						 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
						 			
						 			$('input[aria-selected=true]').removeAttr('aria-selected');
						 			$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-ruedas option').prop('selected',false);
						 		}
							}
							if(!$('#correc-carroceria').is(':checked')) {
								if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
									$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-carroceria option').prop('selected',false);
					 				

					 				
										
						 		} else {
						 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
						 			
						 			$('input[aria-selected=true]').removeAttr('aria-selected');
						 			$('.fIntervenciones-multiselect').addClass('hidden');
					 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
					 				$('.fIntervenciones-correctivos-list select#select-carroceria option').prop('selected',false);
						 		}
							}
						} else if(completed['desplazamiento']){							
							$(".fDetalleInterv-list").remove();
							
							// Borrar informacion de repuestos
							if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
								$('.fIntervenciones-multiselect').addClass('hidden');
				 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
				 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);

					 		} else {
					 			$('.ui-multiselect').addClass('hidden').find('span').text('Repuestos').end().next().addClass('hidden');
					 			
					 			$('input[aria-selected=true]').removeAttr('aria-selected');
					 			$('.fIntervenciones-multiselect').addClass('hidden');
				 				$('.fIntervenciones-multiselect-icon').addClass('hidden');
				 				$('.fIntervenciones-correctivos-list select option').prop('selected',false);
					 		}

					 		// Desactivar todas las actuaciones preventivas 
					 		$('.fIntervenciones-preventivas-list :checked').prop('checked', false)
					 		// Desactivar todas las actuaciones correctivas 
					 		$('.fIntervenciones-correctivos-list :checked').prop('checked', false)


						}

						$('#fU').submit(); 
			        } 
			    },
			    {
					text: "Cancelar",
			        class: 'dialog-cancel-btm', 
			        click: function() { 
						$(this).dialog("close");
						// Volver a permitir scroll en body
						$('body').css('overflow','auto');
						$('body').css('position','static');
			     	}
				}
			]
		});

		// Al redimensionar el browser ajustar la ventana modal al alto del navegador
		$(window).resize(function() {
			$("#dialog").dialog( "option", "maxHeight", $(window).height() * 0.9);
		});

		



		// Mostrar ventana modal
		$('#dialog').dialog('open');

		// COMPONER VENTANA MODAL
		// Recoger datos generales
		// Fecha y hora de intervención
		var dt_intervencion = $.formatDate($('#dt-parte').val(), 1);
		// Unidad 
		var unidad_intervencion = $('#campo-unidad').find('option:selected').text();
		
		// Al completar un desplazamiento
		if(arrayCompleted['desplazamiento']){
			$('#dialog').append('<h4>Desplazamiento</h4>');
			
			
			// Recoger datos especificos del desplazamiento
			// Matrícula
			var matricula_intervencion = $('#campo-matricula').find('option:selected').text();
			// Destino del desplazamiento
			var destino_desplazamiento = $('#campo-des-unidades').find('option:selected').text();
			// Accion de desplazamiento [Recogida o Entregada]
			if($('#despla-recogida').is(':checked')) {
				var accion = 'recoge de';
			} else if($('#despla-entrega').is(':checked')) {
				var accion = 'entrega en';  
			}
			if(unidad_intervencion == destino_desplazamiento){

				if(accion == 'recoge de') {
					$('#dialog').append('<li><strong>' + dt_intervencion + ' - </strong> El vehículo <strong>'+ matricula_intervencion +'</strong> con base en '+ unidad_intervencion +' <strong>se traslada al taller.</strong></li>');
				} else if(accion == 'entrega en') {
					$('#dialog').append('<li><strong>' + dt_intervencion + ' - </strong> El vehículo <strong>'+ matricula_intervencion +'</strong> con base en '+ unidad_intervencion +' <strong>se entrega desde taller.</strong></li>');
				}
			} else {
				$('#dialog').append('<li><strong>' + dt_intervencion + ' - </strong> El vehículo <strong>'+ matricula_intervencion +'</strong> con base en '+ unidad_intervencion +' <strong>se '+ accion +' '+ destino_desplazamiento + '</strong></li>');
			}
			
		}

		if(arrayCompleted['prevenAll']){


			
			// Recoger datos especificos del desplazamiento
			var lav = $('#preven-lavado').is(':checked') ;
			var prev = $('#preven-completo').is(':checked');

			
			$('#dialog').append('<h4>Preventivos y Lavados</h4>');
			$('#dialog').append('<li><strong>' + dt_intervencion + ' - </strong>Realizados en <strong>'+ unidad_intervencion +'</strong> a los siguientes vehículos:</li>');

			$('#dialog').append('<li class="dialog-list-matriculas">');
			$('.dialog-list-matriculas').append('<ul style="margin-top: 10px" class="dialog-listMat">');

			

			$.each($('#campo-matricula').find('option[value!=all]').not('option[value=0]'), function(key, value) {

				var matricula_i = $(this).val();
				var matricula_t = $(this).text();

				

			  	$('.dialog-listMat').append('<li class="row-mat"><strong class="container-mat">'+matricula_t+':</strong><div class="fIU-list-item-container container-ckm"><input class="fIU-list-item-input ckm-container-input" placeholder="Kilometros" type="tel" maxlength="6" onKeyUp="$(this).val($(this).val().replace(/[^\\d]/ig, \'\'))" name="c-km[]" id="km-'+matricula_i+'"/><input type="hidden" value="0" name="c-km[]" disabled></div><div class="fIU-list-item-container radio container-lav"><input  id="L-'+matricula_i+'" class="fIU-list-item-radio container-lav-check" type="checkbox" name="L[]" value="L-'+matricula_i+'"/><input type="hidden" value="0" name="L[]" disabled><label class="fIU-list-item-radio-label container-lav-label" for="L-'+matricula_i+'">Lav.</label></div><div class="fIU-list-item-container radio container-prev"><input id="P-'+matricula_i+'" class="fIU-list-item-radio container-prev-check" type="checkbox" name="P[]" value="P-'+matricula_i+'" /><input type="hidden" value="0" name="P[]" disabled><label  class="fIU-list-item-radio-label container-prev-label" for="P-'+matricula_i+'">Prev.</label></div></li>');
			});

			

			if(lav) {
				$('.container-lav-check').prop('checked', true);
			}
			if (prev){
				$('.container-prev-check').prop('checked', true);
			}

			
		}

		if(arrayCompleted['correcPreven']){
			$('#dialog').append('<h4>Correctivos y Preventivos</h4>');
			
			
			// Recoger datos especificos del desplazamiento
			// Matrícula
			var matricula_intervencion = $('#campo-matricula').find('option:selected').text();
			var motivo_intevencion = $('.fIntervenciones-tipo-list').hasActiveOption().next().text();
			var lugar_reparacion = $('.fIntervenciones-lugarReparacion-list').hasActiveOption().next().text();

			switch(lugar_reparacion) {
				case 'Calle':
				lugar_reparacion = '<span style="font-weight: normal;">la</span> ' + lugar_reparacion;
				break;
				case 'Taller':
				lugar_reparacion = '<span style="font-weight: normal;">el</span> ' + lugar_reparacion;
				break;
			}
			$('#dialog').append('<li style="margin-bottom: 10px"><strong>' + dt_intervencion + ' - </strong> El vehículo <strong>'+ matricula_intervencion +'</strong> con base en '+ unidad_intervencion +' atendido por <strong>'+ motivo_intevencion +'</strong> y reparado en <strong>'+ lugar_reparacion +'</strong> según el siguiente detalle:</li>');


			$('#dialog').append('<li class="dialog-list-reparaciones">');
			$('.dialog-list-reparaciones').append('<ul class="dialog-listPrev">');
	
			$.each($('.fIntervenciones-preventivas-list').getActiveOptions(), function(key, value) {
			  	$('.dialog-listPrev').append('<li><strong>' + value.next().text() +'</strong></li>');
			});

			$('.dialog-list-reparaciones').append('<ul class="dialog-listCorrec">');
			if($.hay_elementos_seleccionados() && !$.hay_errores_detalle()) {
				

				var validador_detalle = $.validar_detalle(false);
				

				$.each(validador_detalle['detalles_completados'], function(key, value) {


					reparacion_completa = validador_detalle['detalles_completados'][key]['reparacion'].trim();
					repuestos_completos = validador_detalle['detalles_completados'][key]['repuestos'].trim();

					// Todos los caracteres que pueden ser de separación se convierten en coma
					repuestos_completos_1filtro =  repuestos_completos.replace(/\;/g, ",").replace(/\./g, ',').replace(/\_/g, ',').replace(/\-/g, ',').replace(/\´/g, ',').replace(/\`/g, ',').replace(/\+/g, ',').replace(/\*/g, ',').replace(/\:/g, ',').replace(/\>/g, ',').replace(/\</g, ',').replace(/\|/g, ',').replace(/\"/g, ',').replace(/\'/g, ',').replace(/\·/g, ',').replace(/\#/g, ',').replace(/\(/g, ',').replace(/\)/g, ',').replace(/\=/g, ',').replace(/\[/g, ',').replace(/\]/g, ',').replace(/\^/g, ',').replace(/\{/g, ',').replace(/\}/g, ',').replace(/\\/g, ',').replace(/\//g, ',').replace(/\¨/g, ',');
					// Detecto las doble comas y las convierto en comas simples
					repuestos_completos_2filtro = repuestos_completos_1filtro.replace(/,,/g, ",");

					// Comprobar si el primer caracter es una coma y suprimirla en tal caso
					if(repuestos_completos_2filtro.substring(0,1) == ",") {
						repuestos_completos_2filtro = repuestos_completos_2filtro.substring(1,repuestos_completos_2filtro.length);
					}

					if(repuestos_completos_2filtro.substring(repuestos_completos_2filtro.length-1,repuestos_completos_2filtro.length) == ",") {
						repuestos_completos_2filtro = repuestos_completos_2filtro.substring(0,repuestos_completos_2filtro.length-1);
					}

					// Separar repuestos por coma

					res_repuestos = repuestos_completos_2filtro.split(',');

					if(res_repuestos.length > 1) {

						repuestos_formateados = "[<strong>Repuestos</strong>: ";
						// Comprobar si el último caracter es una coma y suprimirla en tal caso
						
						
							$.each(res_repuestos, function(i){

								repuestos_formateados_filtro1 = res_repuestos[i].trim();
								repuestos_formateados_filtro2 = repuestos_formateados_filtro1.substring(0,1).toUpperCase() + repuestos_formateados_filtro1.substring(1,repuestos_formateados_filtro1.length).toLowerCase();
								
									repuestos_formateados += repuestos_formateados_filtro2;
								if(i < res_repuestos.length-1){
									repuestos_formateados += ", ";
									
								}
							});	
						repuestos_formateados += "]";
					} else if (repuestos_completos_2filtro.length > 0){
						repuestos_formateados = "[<strong>Repuestos</strong>: " + repuestos_completos_2filtro.substring(0,1).toUpperCase() + repuestos_completos_2filtro.substring(1,repuestos_completos_2filtro.length).toLowerCase() + "]";
					} else {
						repuestos_formateados = "";
					}
						
				
					
				
					reparacion_formateada = reparacion_completa.substring(0,1).toUpperCase() + reparacion_completa.substring(1,reparacion_completa.length).toLowerCase();


				  	$('.dialog-listCorrec').append('<li><strong>' + reparacion_formateada +'</strong> '+ repuestos_formateados +'</li>');
				  });
			}			

			// // Detectar dispositivo
			// var dispositivo = navigator.userAgent.toLowerCase();
			// // Dispositivo es iphone, ipod, ipad, android
			// if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
			
			// 	$.each($('.fIntervenciones-correctivos-list').getActiveOptions(), function(key, value) {
			//   	$('.dialog-listMat').append('<li><strong>' + value.next().text() +'</strong>' +$.getMultiselectValuesMobile($(this).next().next().attr('id')) + '</li>');
			// 	});
				
			// } else {

			// 	$.each($('.fIntervenciones-correctivos-list').getActiveOptions(), function(key, value) {
			//   		$('.dialog-listMat').append('<li><strong>' + value.next().text() +'</strong>' +$.getMultiselectValues($(this).next().next().attr('id'), false, 'string') + '</li>');
			// 	});

			// }	

			


		}

		$('#dialog').wrapInner('<ul class="dialog-list">')
		.find('li').addClass('dialog-list-item')
		.end().find('h4').addClass('dialog-list-title');
		

		$('.container-lav-check').change(function() {
			
			if(!$(this).parent('.container-lav').next().find('.container-prev-check').is(':checked')) {
					$(this).parents('.row-mat').find('.ckm-container-input').controlFields('element', 'off', -1).next('input[type=hidden]').attr('disabled', false);
			} 
			if($(this).is(':checked')){
				$(this).parents('.row-mat').find('.ckm-container-input').controlFields('element', 'on', 0).next('input[type=hidden]').attr('disabled', true);
			}

		});

		$('.container-prev-check').change(function() {
			
			if(!$(this).parent('.container-prev').prev().find('.container-lav-check').is(':checked')) {

				$(this).parents('.row-mat').find('.ckm-container-input').controlFields('element', 'off', -1).next('input[type=hidden]').attr('disabled', false);
			}
			if($(this).is(':checked')){
				$(this).parents('.row-mat').find('.ckm-container-input').controlFields('element', 'on', 0).next('input[type=hidden]').attr('disabled', true);

				
			}
		});


		// Desactivar scroll del background (body) mientras muestra ventana modal
		$('body').css('overflow','hidden');
		$('body').css('position','fixed');
		$('body').css('width','100%'); 
	

		// Detectar dispositivo
		var dispositivo = navigator.userAgent.toLowerCase();
		// Dispositivo es iphone, ipod, ipad, android
		if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
			// Si hay focus está el teclado abierto
			// Mostramos la modal en top 


  			if($(':focus')) {
  				$(':focus').blur();
  				$('.ui-dialog').addClass('ui-dialog-top');
  			}
 		}


	}


	// Funcion para comprobar si hay options seleccionadas en un multiselect
	// Se pasa como parametro el nombre de la clase que contiene el multiselect sin '.'
	// El nombre de esta clase coincide con la id del campo donde inicializamos el multiselect
	$.multiselectActive = function(field) {	
		out = false;
		$(".ui-multiselect-checkboxes").parent('.'+field).find('input:checkbox').each(function(i4,v4){
			// Recorrer cada optión y verificar si hay selected
			if($(this).attr('aria-selected') == "true") {	
				out = true;
				return false; // Romper bucle
						
			} else {
				out = false;		
			}
		});
 
		return out;
	}

	$.getMultiselectValues = function(field, inicial, type) {

		var out = [];

		$(".ui-multiselect-checkboxes").parent('.'+field).find('input:checkbox').each(function(i4,v4){
			
			
			// Recorrer cada optión y verificar si hay selected
			if(inicial) {
				if($(this).filter('input[value^="' + inicial +'"]').attr('aria-selected') == "true") {	
					
					out.push($(this).val().replace(inicial,' '));		
				}
			} else {
				if($(this).attr('aria-selected') == "true") {	
					// Si se quiere coger el valor en lugar del texto del label cambiar $(this).next().text() por '$(this).val()'
					// Se puede ver que en el caso del if(inicial) = true se coge el valor 
					out.push($(this).next().text());		
				}
			}
		});

		if(type == "array") {
			return out;
		} else if( type == "string") {
			var string_out = ": ";
			$.each(out, function(i,v) {
				string_out += v + ", ";
			});
			string_out = string_out.substring(0, string_out.length-2);
			return string_out;
		}
		
	}

	$.getMultiselectValuesMobile = function(field) {

		var foo = []; 

		$('#'+field + ' :selected').map(function(i, selected){ 
		 foo[i] = $(selected).text(); 
		});
		

		var string_out = ": ";
			$.each(foo, function(i,v) {
				string_out += v + ", ";
			});
			string_out = string_out.substring(0, string_out.length-2);
			
		return string_out;
		
	}

	// Función para comprobar si hay errores o bloques completados en el formulario
	// Comprobar errores -> array = errors
	// Comprobar bloques completados -> array = completed
	$.hasEC = function(array) {

		// Si hay errores en la fecha aviso paramos la función y devolvemos true
		// La fecha de aviso siempre tiene completed false
		if (array['date']) { return true; }
		
		
		// Si está completado el modulo de desplazamientos
		if (array['desplazamiento']) { return true; }
		// Si está completado el modulo de correctivos y preventivos
		if (array['correcPreven']) { return true; }

		// Si está completado el modulo de preventivos a todas los vehículos (Lavados y M. Preventivos)
		if (array['prevenAll']) { return true; }

		// Si la ejecución llega a esta linea es que no hay errores o bloques completados (según el array de entrada)
		return false;
		
	}


	// Función para crear alertas error o warning
	$.printMensajes = function(warningMsg) {


		// Crear contenedor para las alertas
		$('body > div.alert').remove();
		$('body').append('<div class="alert">');
		$('.alert').hide(); // Ocultar de momento
		
		// Si hay errores rellenar el contenedor con la lista de errores
		if($.hasEC(errors)){
			$('.alert').addClass('error');
			if (errors['date']) { 
				$('.alert').append('<li class="alert-list-item">' + errors['date'] + '</li>'); 
			}

			$('.alert').wrapInner('<ul class="alert-list">');
		
		// Si no hay errores ni bloques completados rellenar el contenedor con warning	
		} else if(
			!$.hasEC(errors) && 
			!$.hasEC(completed)
		){
			$('.alert').addClass('warning');
			$('.alert').append('<li class="alert-list-item">'+ warningMsg +'</li>')
			.wrapInner('<ul class="alert-list">');
		}	

		// Función que muestra las alertas y oculta con transición de fundido
		$(window).resize(function () {
				if ($(window).width() > 485) {
				    $(".alert").css({
						zIndex: 3,
						position: 'fixed',
						// Se alinea en la horizontal cogiendo medidas del navegador 
						left: ($(window).width() - $('.alert').outerWidth()) / 2,
						top: ($(window).height() - $('.alert').outerHeight() - 20)

					});
				} else {
					$(".alert").css({
						zIndex: 3,
						position: 'fixed',
						// Se alinea en la horizontal cogiendo medidas del navegador 
						left: ($(window).width() - $('.alert').outerWidth()) / 2,
						top: (20)

					});  	
				}
				
			});
		// El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
		// Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
		$(".alert").fadeIn(500, function(){
			$('.alert').delay(5000 * $('.alert-list-item').size())
			.fadeOut(500);
		});	
			// Ejecutar la función que muestra y oculta las alertas
			$(window).resize();		
	}


	//Array que almacena los bloques cumplimentados correctamente.
	//(![date], ["avisos"], ["prevlav"] e ["itv"])
	var completed = new Array();
	
	// Array que almacena los errores al cumplimentar distintos bloques.
	// ([date], ["avisos"], !["prevlav"] e ["itv"])
	var errors = new Array();

	// VALIDACIÓN DATE (Fecha y hora avisos)
	// Obtener la Fecha aviso, fecha máxima y fecha minima
	var dateParte = $('#dt-parte').val().replace('-','/');		
	var maxdateParte = date('c').split("+")[0].replace('-','/'); // Fecha actial

	var mindateParte = date("Y-m-d" + "\T" +"H:i:s" , strtotime('-5 day -1 hours', strtotime(maxdateParte))).replace('-','/').replace('UTC','T'); // 5 días antes y 1 hora para considerar el tiempo que el usuario tarda en rellenar el formulario.

	// Si se introduce una fecha y hora correctamente
	if(dateParte){
		// Si la fecha pasada es menor o igual a la fecha máxima(actual) 
		if(dateParte <= maxdateParte) {
			// Si la fecha pasada es mayor o igual a la minima (5 días y 1 hora anterior a la actual)
			if(dateParte >= mindateParte) {
				$('#dt-parte').classToContainer('input-error', 'r');
				errors["date"] = false;
				completed["date"] = false;
			// Si la fecha pasada es menor a la fecha minima
			} else {
				errors["date"] = "No pueden grabarse intervenciones con más de 5 días de retraso";
				$('#dt-parte').classToContainer('input-error', 'a');
				completed["date"] = false;
			}
		// Si la fecha pasada es mayor a la fecha maxima (actual)
		} else {
			$('#dt-parte').classToContainer('input-error', 'a');
			errors["date"] = "La fecha debe ser anterior a la actual";
			completed["date"] = false;
		}
	// Si no se introduce fecha y hora o se introduce incorrectamente
	} else {
		$('#dt-parte').classToContainer('input-error', 'a');
		errors["date"] = "Campo fecha mal cumplimentado";
		completed["date"] = false;
	}


	
	// VALIDACION DE BLOQUES COMPLETADOS 
	// Si No hay errores y no hay bloques completados
	if (
		!$.hasEC(errors) && 
		!$.hasEC(completed)
	) {
		
		// Mostrar warnings en los campos
		// Si no hay introducida una unidad marcar warning en campo de unidad y subir
		if($('#campo-unidad').val() == 0) {
			$('#campo-matricula').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'r');

			$('#campo-unidad').classToContainer('input-warning', 'a');
			


			  if ($(window).width() > 485) {
			    $('html,body').animate({scrollTop: $("body").offset().top}, 1000);
			  } else {
			  	$('html,body').animate({scrollTop: $("#fU").offset().top + 13}, 1000);
			  }
	



			var warningMsg = "Selecciona unidad";

		// Si hay unidad introducida pero no hay matrícula marca warning en campo matrícula y subir	
		} else if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() == 0
			) {
			
			$('#campo-unidad').classToContainer('input-warning', 'r');
			$('#campo-matricula').classToContainer('input-warning', 'a');

			$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'r');

			 if ($(window).width() > 485) {
			    $('html,body').animate({scrollTop: $("body").offset().top}, 1000);
			  } else {
			  	$('html,body').animate({scrollTop: $("#dt-parte").offset().top + 23}, 1000);
			  }

			var warningMsg = "Selecciona matrícula";
		}
		
		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& !$('.fIntervenciones-tipo-list').hasActiveOption()
		) {
			$('#campo-unidad, #campo-matricula').classToContainer('input-warning', 'r');
			
			

			$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'a');
				

			

			if ($(window).width() > 485) {
			   $('html,body').animate({scrollTop: $("body").offset().top}, 1000);
			  } else {
			  	$('html,body').animate({scrollTop: $("#campo-matricula").offset().top + 44}, 1000);
			  }



			var warningMsg = "Selecciona motivo de intervención";
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('#campo-matricula').val() != 'all' 
			&& $('.fIntervenciones-tipo-list').hasActiveOption() 
			&& !$('.fIntervenciones-lugarReparacion-list').hasActiveOption()
		) {
			$('#campo-unidad, #campo-matricula').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list').controlValidateOptions('w', 'r');


			$('.fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'a');
				

			$('html,body').animate({scrollTop: $("#tipo-preventivas").offset().top + 13}, 1000);

			var warningMsg = "Selecciona lugar de reparación";
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('.fIntervenciones-tipo-list').hasActiveOption() 
			&& $('.fIntervenciones-lugarReparacion-list').hasActiveOption()
			&& !$('.fIntervenciones-preventivas-list').hasActiveOption()
			&& !$.hay_elementos_seleccionados()
		) {
			$('#campo-unidad, #campo-matricula').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'r');
			$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'a');	
			$('.campo-elemento').classToContainer('input-warning', 'a');


			// PROVISION, HAY QUE REVISAR POR QUE SE ENCIENDEN LLEGADOS AQUI LAS ALERTAS DE ACCION
			$('.campo-accion').classToContainer('input-warning', 'r'); 



			$('html,body').animate({scrollTop: $("#lugar-unidad").offset().top + 13}, 1000);

			
			var warningMsg = "Selecciona preventivos y/o correctivos";
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('.fIntervenciones-tipo-list').hasActiveOption() 
			&& $('.fIntervenciones-lugarReparacion-list').hasActiveOption()
			&& $.hay_elementos_seleccionados() 
			&& $.hay_errores_detalle()
		) {
			$('#campo-unidad, #campo-matricula').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list').controlValidateOptions('w', 'r');
			$('.fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');				
			$('.campo-elemento').classToContainer('input-warning', 'r');
			
			var alertas_multiples = true;
			// var warningMsg = "Hay correctivos incompletos";
		}


		

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('#campo-matricula').val() != 'all'
			&& !$('#tipo-desplazamiento').is(':checked')
			&& $('.fIntervenciones-tipo-list').hasActiveOption() 
			&& $('.fIntervenciones-lugarReparacion-list').hasActiveOption()
			&& ((!$.hay_errores_detalle() && $('.fIntervenciones-preventivas-list').hasActiveOption()) || 
				!$('.fIntervenciones-preventivas-list').hasActiveOption() && $.hay_elementos_seleccionados() && !$.hay_errores_detalle())
			
		) {

			$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida, #campo-des-unidades, #preven-completo, #preven-lavado').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
			$('.fDetalleInterv-list select').classToContainer('input-warning','r');

			completed["correcPreven"] = true;
	
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('#tipo-desplazamiento').is(':checked')
			&& (!$('#despla-entrega').is(':checked') && !$('#despla-recogida').is(':checked'))
		) {
			$('#campo-unidad, #campo-matricula').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
			$('.fDetalleInterv-list select').classToContainer('input-warning','r');
			$('#despla-entrega, #despla-recogida').classToContainer('input-warning', 'a');
			
			var warningMsg = "Selecciona si el vehículo es entregado o recogido";
			

		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('#tipo-desplazamiento').is(':checked')
			&& ($('#despla-entrega').is(':checked') || $('#despla-recogida').is(':checked'))
			&& $('#campo-des-unidades').val() == 0
		) {
			$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
			$('.fDetalleInterv-list select').classToContainer('input-warning','r');
			$('#campo-des-unidades').classToContainer('input-warning', 'a');
			var warningMsg = "Selecciona la unidad de destino del desplazamiento";

		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() != 0 
			&& $('#tipo-desplazamiento').is(':checked')
			&& ($('#despla-entrega').is(':checked') || $('#despla-recogida').is(':checked'))
			&& $('#campo-des-unidades').val() != 0
		) {
			$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida, #campo-des-unidades, #preven-completo, #preven-lavado').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
			$('.fDetalleInterv-list select').classToContainer('input-warning','r');
			completed["desplazamiento"] = true;
	

			
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() == 'all' 
			&& $('#tipo-preventivas').is(':checked')
			&& $('#lugar-unidad').is(':checked')
			&& (!$('#preven-completo').is(':checked') && !$('#preven-lavado').is(':checked'))
		) {

			$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida, #campo-des-unidades').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
			
			$('.fDetalleInterv-list select').classToContainer('input-warning','r');
			$('#preven-completo').classToContainer('input-warning', 'a');
			$('#preven-lavado').classToContainer('input-warning', 'a');

			
			var warningMsg = "Selecciona alguna actividad preventiva";

			
		}

		if(
			$('#campo-unidad').val() != 0 
			&& $('#campo-matricula').val() == 'all' 
			&& $('#tipo-preventivas').is(':checked')
			&& $('#lugar-unidad').is(':checked')
			&& ($('#preven-completo').is(':checked') || $('#preven-lavado').is(':checked'))
		) {


			$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida, #campo-des-unidades, #preven-completo, #preven-lavado').classToContainer('input-warning', 'r');
			$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');

			$('.fDetalleInterv-list select').classToContainer('input-warning','r');

			completed["prevenAll"] = true;
	
			
			
		}

		if(
			!$.hasEC(errors) && 
			$.hasEC(completed)
		) {
			
			
			$('.alert').hide(); // Ocultar alertas

			// Traer ventana modal
			$.showModal(completed);
			$('.ui-dialog-content').scrollTop();
		}	


		if(alertas_multiples) {

			$("*").classToContainer('input-warning', 'r'); // Oculto todos los warnings  que haya
			$("*").classToContainer('input-error', 'r');
			$('body > div.alert').remove(); // Oculto alertas si las hay

			validador_detalle = $.validar_detalle(false);


			// Crear la caja de alertas
			$('body').append('<div class="alert">');
			$('.alert').hide(); // Ocultar de momento

			var primera_linea = new Array();
			primera_linea['posicion'] = 0;
			primera_linea['tipo'] = false;
			var control_errores = false;

			$.each(validador_detalle['errores_detalle'], function(i,v){
				

				if(validador_detalle['errores_detalle'][i]['posicion']){
					control_errores = true;
						
					if(validador_detalle['errores_detalle'][i]['posicion'] > primera_linea['posicion'] && primera_linea['posicion'] == 0) {


						primera_linea['posicion'] = validador_detalle['errores_detalle'][i]['posicion'];
						primera_linea['tipo'] = validador_detalle['errores_detalle'][i]['tipo'];
					}							

					$('.alert').append('<li class="alert-list-item">Detalle ' + validador_detalle['errores_detalle'][i]['posicion'] + ': '+ validador_detalle['errores_detalle'][i]['msg'] +'</li>');	
				}
				
			});
				

			if(control_errores) {

				
				$('.alert').addClass('warning');

				// Mostrar y ocultar al rato las alertas

			$(window).resize(function () {
				if ($(window).width() > 485) {
					$(".alert").css({
							zIndex: 3,
							position: 'fixed',
							// Se alinea en la horizontal cogiendo medidas del navegador 
							left: ($(window).width() - $('.alert').outerWidth()) / 2,
							top: ($(window).height() - $('.alert').outerHeight() - 20)

					});
				} else {
					$(".alert").css({
							zIndex: 3,
							position: 'fixed',
							// Se alinea en la horizontal cogiendo medidas del navegador 
							left: ($(window).width() - $('.alert').outerWidth()) / 2,
							top: (20)

					});
				}
			});

			// El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
			// Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
			$(".alert").fadeIn(500, function(){
				$('.alert').delay(5000 * $('.alert-list-item').size())
				.fadeOut(500);
			});	

			$('html,body').animate({scrollTop: $(primera_linea['tipo']).offset().top - 150}, 1000);
			// Ejecutar la función que muestra y oculta las alertas
			$(window).resize();	

			}





		} else {
			$.printMensajes(warningMsg); // Mostrar alertas
		}






	
	// Si hay errores
	} else if(
		$.hasEC(errors)
	) {
		
		$('#campo-unidad, #campo-matricula, #despla-entrega, #despla-recogida, #campo-des-unidades, #preven-completo, #preven-lavado').classToContainer('input-warning', 'r');
		$('.fIntervenciones-tipo-list, .fIntervenciones-lugarReparacion-list, .fIntervenciones-correctivos-list, .fIntervenciones-preventivas-list').controlValidateOptions('w', 'r');
		$('html,body').animate({scrollTop: $("body").offset().top}, 1000);
		$.printMensajes(false); // Mostrar alertas
	
	// Si no hay errores y hay completados
	} 

	return false;

});

// Declarar funciones equivalentes a date() y strtotime() de PHP
function date(format, timestamp) {
  //  discuss at: http://phpjs.org/functions/date/
  // original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
  // original by: gettimeofday
  //    parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: MeEtc (http://yass.meetcweb.com)
  // improved by: Brad Touesnard
  // improved by: Tim Wiel
  // improved by: Bryan Elliott
  // improved by: David Randall
  // improved by: Theriault
  // improved by: Theriault
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault
  // improved by: Thomas Beaucourt (http://www.webapp.fr)
  // improved by: JT
  // improved by: Theriault
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  // improved by: Theriault
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: majak
  //    input by: Alex
  //    input by: Martin
  //    input by: Alex Wilson
  //    input by: Haravikk
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: majak
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: omid (http://phpjs.org/functions/380:380#comment_137122)
  // bugfixed by: Chris (http://www.devotis.nl/)
  //        note: Uses global: php_js to store the default timezone
  //        note: Although the function potentially allows timezone info (see notes), it currently does not set
  //        note: per a timezone specified by date_default_timezone_set(). Implementers might use
  //        note: this.php_js.currentTimezoneOffset and this.php_js.currentTimezoneDST set by that function
  //        note: in order to adjust the dates in this function (or our other date functions!) accordingly
  //   example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
  //   returns 1: '09:09:40 m is month'
  //   example 2: date('F j, Y, g:i a', 1062462400);
  //   returns 2: 'September 2, 2003, 2:26 am'
  //   example 3: date('Y W o', 1062462400);
  //   returns 3: '2003 36 2003'
  //   example 4: x = date('Y m d', (new Date()).getTime()/1000);
  //   example 4: (x+'').length == 10 // 2009 01 09
  //   returns 4: true
  //   example 5: date('W', 1104534000);
  //   returns 5: '53'
  //   example 6: date('B t', 1104534000);
  //   returns 6: '999 31'
  //   example 7: date('W U', 1293750000.82); // 2010-12-31
  //   returns 7: '52 1293750000'
  //   example 8: date('W', 1293836400); // 2011-01-01
  //   returns 8: '52'
  //   example 9: date('W Y-m-d', 1293974054); // 2011-01-02
  //   returns 9: '52 2011-01-02'

  var that = this;
  var jsdate, f;
  // Keep this here (works, but for code commented-out below for file size reasons)
  // var tal= [];
  var txt_words = [
    'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  // trailing backslash -> (dropped)
  // a backslash followed by any character (including backslash) -> the character
  // empty string -> empty string
  var formatChr = /\\?(.?)/gi;
  var formatChrCb = function(t, s) {
    return f[t] ? f[t]() : s;
  };
  var _pad = function(n, c) {
    n = String(n);
    while (n.length < c) {
      n = '0' + n;
    }
    return n;
  };
  f = {
    // Day
    d: function() { // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function() { // Shorthand day name; Mon...Sun
      return f.l()
        .slice(0, 3);
    },
    j: function() { // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function() { // Full day name; Monday...Sunday
      return txt_words[f.w()] + 'day';
    },
    N: function() { // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function() { // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      var i = j % 10;
      if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
        i = 0;
      }
      return ['st', 'nd', 'rd'][i - 1] || 'th';
    },
    w: function() { // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function() { // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j());
      var b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5);
    },

    // Week
    W: function() { // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      var b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function() { // Full month name; January...December
      return txt_words[6 + f.n()];
    },
    m: function() { // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function() { // Shorthand month name; Jan...Dec
      return f.F()
        .slice(0, 3);
    },
    n: function() { // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function() { // Days in month; 28...31
      return (new Date(f.Y(), f.n(), 0))
        .getDate();
    },

    // Year
    L: function() { // Is leap year?; 0 or 1
      var j = f.Y();
      return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
    },
    o: function() { // ISO-8601 year
      var n = f.n();
      var W = f.W();
      var Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function() { // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function() { // Last two digits of year; 00...99
      return f.Y()
        .toString()
        .slice(-2);
    },

    // Time
    a: function() { // am or pm
      return jsdate.getHours() > 11 ? 'pm' : 'am';
    },
    A: function() { // AM or PM
      return f.a()
        .toUpperCase();
    },
    B: function() { // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2;
      // Hours
      var i = jsdate.getUTCMinutes() * 60;
      // Minutes
      var s = jsdate.getUTCSeconds(); // Seconds
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function() { // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function() { // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function() { // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function() { // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function() { // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function() { // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function() { // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function() { // Timezone identifier; e.g. Atlantic/Azores, ...
      // The following works, but requires inclusion of the very large
      // timezone_abbreviations_list() function.
      /*              return that.date_default_timezone_get();
       */
      throw 'Not supported (see source code of date() for timezone on how to add support)';
    },
    I: function() { // DST observed?; 0 or 1
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      var a = new Date(f.Y(), 0);
      // Jan 1
      var c = Date.UTC(f.Y(), 0);
      // Jan 1 UTC
      var b = new Date(f.Y(), 6);
      // Jul 1
      var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
      return ((a - c) !== (b - d)) ? 1 : 0;
    },
    O: function() { // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset();
      var a = Math.abs(tzo);
      return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    },
    P: function() { // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return (O.substr(0, 3) + ':' + O.substr(3, 2));
    },
    T: function() { // Timezone abbreviation; e.g. EST, MDT, ...
      // The following works, but requires inclusion of the very
      // large timezone_abbreviations_list() function.
      /*              var abbr, i, os, _default;
      if (!tal.length) {
        tal = that.timezone_abbreviations_list();
      }
      if (that.php_js && that.php_js.default_timezone) {
        _default = that.php_js.default_timezone;
        for (abbr in tal) {
          for (i = 0; i < tal[abbr].length; i++) {
            if (tal[abbr][i].timezone_id === _default) {
              return abbr.toUpperCase();
            }
          }
        }
      }
      for (abbr in tal) {
        for (i = 0; i < tal[abbr].length; i++) {
          os = -jsdate.getTimezoneOffset() * 60;
          if (tal[abbr][i].offset === os) {
            return abbr.toUpperCase();
          }
        }
      }
      */
      return 'UTC';
    },
    Z: function() { // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function() { // ISO-8601 date.
      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    },
    r: function() { // RFC 2822
      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    },
    U: function() { // Seconds since UNIX epoch
      return jsdate / 1000 | 0;
    }
  };
  this.date = function(format, timestamp) {
    that = this;
    jsdate = (timestamp === undefined ? new Date() : // Not provided
      (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
      new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    );
    return format.replace(formatChr, formatChrCb);
  };
  return this.date(format, timestamp);
}
function strtotime(text, now) {
  //  discuss at: http://phpjs.org/functions/strtotime/
  //     version: 1109.2016
  // original by: Caio Ariede (http://caioariede.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Caio Ariede (http://caioariede.com)
  // improved by: A. Matías Quezada (http://amatiasq.com)
  // improved by: preuter
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Mirko Faber
  //    input by: David
  // bugfixed by: Wagner B. Soares
  // bugfixed by: Artur Tchernychev
  //        note: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
  //   example 1: strtotime('+1 day', 1129633200);
  //   returns 1: 1129719600
  //   example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
  //   returns 2: 1130425202
  //   example 3: strtotime('last month', 1129633200);
  //   returns 3: 1127041200
  //   example 4: strtotime('2009-05-04 08:30:00 GMT');
  //   returns 4: 1241425800

  var parsed, match, today, year, date, days, ranges, len, times, regex, i, fail = false;

  if (!text) {
    return fail;
  }

  // Unecessary spaces
  text = text.replace(/^\s+|\s+$/g, '')
    .replace(/\s{2,}/g, ' ')
    .replace(/[\t\r\n]/g, '')
    .toLowerCase();

  // in contrast to php, js Date.parse function interprets:
  // dates given as yyyy-mm-dd as in timezone: UTC,
  // dates with "." or "-" as MDY instead of DMY
  // dates with two-digit years differently
  // etc...etc...
  // ...therefore we manually parse lots of common date formats
  match = text.match(
    /^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/);

  if (match && match[2] === match[4]) {
    if (match[1] > 1901) {
      switch (match[2]) {
        case '-':
          { // YYYY-M-D
            if (match[3] > 12 || match[5] > 31) {
              return fail;
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // YYYY.M.D is not parsed by strtotime()
            return fail;
          }
        case '/':
          { // YYYY/M/D
            if (match[3] > 12 || match[5] > 31) {
              return fail;
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
      }
    } else if (match[5] > 1901) {
      switch (match[2]) {
        case '-':
          { // D-M-YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // D.M.YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '/':
          { // M/D/YYYY
            if (match[1] > 12 || match[3] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
      }
    } else {
      switch (match[2]) {
        case '-':
          { // YY-M-D
            if (match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)) {
              return fail;
            }

            year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];
            return new Date(year, parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // D.M.YY or H.MM.SS
            if (match[5] >= 70) { // D.M.YY
              if (match[3] > 12 || match[1] > 31) {
                return fail;
              }

              return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
            }
            if (match[5] < 60 && !match[6]) { // H.MM.SS
              if (match[1] > 23 || match[3] > 59) {
                return fail;
              }

              today = new Date();
              return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000;
            }

            return fail; // invalid format, cannot be parsed
          }
        case '/':
          { // M/D/YY
            if (match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)) {
              return fail;
            }

            year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];
            return new Date(year, parseInt(match[1], 10) - 1, match[3],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case ':':
          { // HH:MM:SS
            if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
              return fail;
            }

            today = new Date();
            return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
              match[1] || 0, match[3] || 0, match[5] || 0) / 1000;
          }
      }
    }
  }

  // other formats and "now" should be parsed by Date.parse()
  if (text === 'now') {
    return now === null || isNaN(now) ? new Date()
      .getTime() / 1000 | 0 : now | 0;
  }
  if (!isNaN(parsed = Date.parse(text))) {
    return parsed / 1000 | 0;
  }

  date = now ? new Date(now * 1000) : new Date();
  days = {
    'sun': 0,
    'mon': 1,
    'tue': 2,
    'wed': 3,
    'thu': 4,
    'fri': 5,
    'sat': 6
  };
  ranges = {
    'yea': 'FullYear',
    'mon': 'Month',
    'day': 'Date',
    'hou': 'Hours',
    'min': 'Minutes',
    'sec': 'Seconds'
  };

  function lastNext(type, range, modifier) {
    var diff, day = days[range];

    if (typeof day !== 'undefined') {
      diff = day - date.getDay();

      if (diff === 0) {
        diff = 7 * modifier;
      } else if (diff > 0 && type === 'last') {
        diff -= 7;
      } else if (diff < 0 && type === 'next') {
        diff += 7;
      }

      date.setDate(date.getDate() + diff);
    }
  }

  function process(val) {
    var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
      type = splt[0],
      range = splt[1].substring(0, 3),
      typeIsNumber = /\d+/.test(type),
      ago = splt[2] === 'ago',
      num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

    if (typeIsNumber) {
      num *= parseInt(type, 10);
    }

    if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
      return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
    }

    if (range === 'wee') {
      return date.setDate(date.getDate() + (num * 7));
    }

    if (type === 'next' || type === 'last') {
      lastNext(type, range, num);
    } else if (!typeIsNumber) {
      return false;
    }

    return true;
  }

  times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
    '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
    '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
  regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

  match = text.match(new RegExp(regex, 'gi'));
  if (!match) {
    return fail;
  }

  for (i = 0, len = match.length; i < len; i++) {
    if (!process(match[i])) {
      return fail;
    }
  }

  // ECMAScript 5 only
  // if (!match.every(process))
  //    return false;

  return (date.getTime() / 1000);
}

var dispositivo = navigator.userAgent.toLowerCase();
		// Dispositivo es iphone, ipod, ipad, android
		if(dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
			
 		} else {
 			$(".select-repuestos-correct").multiselect();
 		}

 		var nua = navigator.userAgent;
	// Es navegador nativo Android?
	var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	if (is_android) {
		
		$('body').empty();
		$('body').html("<a href='https://play.google.com/store/apps/details?id=com.android.chrome&hl=es'>Descarga Google Chrome</a>");
	}


// Comprueba si hay opciones activas en un bloque
$.fn.hasActiveOption = function() {	
		out = false;
		$(this).find('input:checkbox, input:radio').each(function(i,v){
			// Recorrer cada optión y verificar si hay selected
			if($(v).is(':checked')) {	
				out = $(v);
				return false; // Romper bucle
						
			} else {
				out = false;		
			}
		});
 
		return out;
}


$.fn.getActiveOptions = function() {
	var out = [];

	$(this).find('input:checkbox, input:radio').each(function(i,v){
		if($(v).is(':checked')) {
			out.push($(v));
		}

	});

	return out;
}

// Comprueba si hay opciones activas en un bloque
$.fn.controlValidateOptions = function(tipo, accion) {	
		out = false;
		$(this).find('input:checkbox, input:radio').each(function(i,v){
			// Recorrer cada optión y verificar si hay selected

			if(tipo == 'w' && accion == "a") {
				$(v).classToContainer('input-warning', 'a');	
			} else if (tipo == 'w' && accion == "r") {
				$(v).classToContainer('input-warning', 'r');
			} else if (tipo == 'e' && accion == "a") {
				$(v).classToContainer('input-error', 'a');
			} else if (tipo == 'e' && accion == "r") {
				$(v).classToContainer('input-error', 'r');
			}
		});
 
		return out;
}

// Extender objetos jQuery
	// Metodo para añadir o remover clases a los contenedores de elementos
	$.fn.classToContainer = function(nombreClase, type) {

		switch(type) {
			case 'a': 
		       	return $(this).parent().addClass(nombreClase);
		        break;
		    case 'r': 
		        return $(this).parent().removeClass(nombreClase);
		        break;
		    case 't': 
		        return $(this).parent().toggleClass(nombreClase);
		        break;      
		}
			
	}


	// Metodo para captar campos sin rellenar de el detalle de la repracion
	// Si nuevoDetalle es true la función además captará las lineas sin nada relleno
	$.validar_detalle = function(nuevoDetalle) {	
		var out = new Array();
		var linea_incompleta = false;
		var errores_detalle = new Array();

		var detalles_completados = new Array();
		var contador_completados = 0;
		
		$('.campo-elemento').each(function(i,v) {

			// Defino array de arrays en esta posición
			errores_detalle[i] = [];
			
			// Al seleccionar un elemento de la lista que no sea 'Nuevo Elemento'
			if(
				$(this).find('option:selected').val() != 0 && 
				!$(this).parents('.fDetalleInterv-list-item').find('.input-nuevo').length
			){
				
				// Si hay seleccionada una acción y no existe el input de nueva accion
				if(
					$(this).parents('.fDetalleInterv-list-item').next().next().find('.campo-accion').find('option:selected').val() != 0 &&
					!$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length

				) {
						
					//AQui tiene que ir un controlador de aciertos
					// COMPLETO
					

					
					var elemento_completado = $(this).find('option:selected').text();
					var especificacion_completado = "";
					var accion_completado = $(this).parents('.fDetalleInterv-list-item').next().next().find('select option:selected').text();
					var repuestos_completado = "";

					if(
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length == 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().find('select').val() != 0

					) {
						
						especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('select option:selected').text();
					} else if(
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length != 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val() != ""

						) {
						
						especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val();
					}

					if(
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').length == 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('select').val() != 0

					) {
						
						repuestos_completado = $(this).parents('.fDetalleInterv-list-item').next().next().next().find('select option:selected').text();
					} else if(
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').length != 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val() != ""

						) {
						
						repuestos_completado = $(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val();
					} 


					detalles_completados[contador_completados] = [];
					detalles_completados[contador_completados]['reparacion'] = accion_completado;
					detalles_completados[contador_completados]['reparacion'] += " " +elemento_completado;

					if(especificacion_completado != "") {
						detalles_completados[contador_completados]['reparacion'] += " " + especificacion_completado;						
					}
					detalles_completados[contador_completados]['repuestos'] = repuestos_completado;
					
					contador_completados++;
					// detalles_completados[contador_completados]['repuestos'] = ; 



				// Si no hay seleccionada una acción y no existte el input de accion
				} else if(
					$(this).parents('.fDetalleInterv-list-item').next().next().find('.campo-accion').find('option:selected').val() == 0 &&
					!$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length

				) {
					
					linea_incompleta = true;

					$(this).parents('.fDetalleInterv-list-item').next().next().find('.campo-accion').classToContainer('input-warning', 'a');


					errores_detalle[i]['posicion'] = i + 1;
					errores_detalle[i]['tipo'] = $(this).parents('.fDetalleInterv-list-item').next().next().find('.campo-accion');
					errores_detalle[i]['msg'] = 'Campo de acción';

				
				// Si no hay seleccionada una accion y existe el input de accion vacio
				} else if(
					$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length &&
					$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val() == ""
				) {
					
					linea_incompleta = true;
					$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').classToContainer('input-warning', 'a');
					
					errores_detalle[i]['posicion'] = i + 1;
					errores_detalle[i]['tipo'] = $(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo');
					errores_detalle[i]['msg'] = 'Campo de acción';

				// Si no hay seleccionad auna accion y existe el input de accion lleno
				} else if(
					$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length &&
					$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val() != ""
				) {
					// COMPLETO
					
					var elemento_completado = $(this).find('option:selected').text();

					var especificacion_completado = "";
					var accion_completado = $(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val();
					var repuestos_completado = "";
					if(
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length == 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().find('select').val() != 0

					) {
						especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('select option:selected').text();
					} else if(
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length != 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val() != ""

						) {
						especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val();
					} 


					if(
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').length == 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('select').val() != 0

					) {
						
						repuestos_completado = $(this).parents('.fDetalleInterv-list-item').next().next().next().find('select option:selected').text();
					} else if(
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').length != 0 &&
						$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val() != ""

						) {
						
						repuestos_completado = $(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val();
					} 


					detalles_completados[contador_completados] = [];

					detalles_completados[contador_completados]['reparacion'] = accion_completado;
					detalles_completados[contador_completados]['reparacion'] += " " +elemento_completado;

					if(especificacion_completado != "") {
						detalles_completados[contador_completados]['reparacion'] += " " + especificacion_completado;						
					}

					detalles_completados[contador_completados]['repuestos'] = repuestos_completado;
					
					contador_completados++;



				}
			
			// Si no hay ningún elemento seleccionado y no está seleccionado 'Nuevo Elemento'
			} else {
				
				if(
					$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').length &&
					$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').val() == ""
				){ 

					
					if(nuevoDetalle) {
						linea_incompleta = true;
						$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').classToContainer('input-warning', 'a');
						
						errores_detalle[i]['posicion'] = i + 1;
						errores_detalle[i]['tipo'] = $(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo');
						errores_detalle[i]['msg'] = 'Campo de elemento';
					}
					
				} else if (
					$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').length &&
					$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').val() != ""

				) {
					
					if(
						$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length &&
						$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val() == ""
					) {
						
						linea_incompleta = true;
						
						$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').classToContainer('input-warning', 'a');
						
						errores_detalle[i]['posicion'] = i + 1;
						errores_detalle[i]['tipo'] = $(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo');
						errores_detalle[i]['msg'] = 'Campo de accion';

					} else if(
						$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').length &&
						$(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val() != ""
					){
						
						// COMPLETO									
						var elemento_completado = $(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').val();
						var especificacion_completado = "";
						var accion_completado = $(this).parents('.fDetalleInterv-list-item').next().next().find('input.input-nuevo').val();
						var repuestos_completado = "";
						if(
							$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length == 0 &&
							$(this).parents('.fDetalleInterv-list-item').next().find('select').val() != 0

						) {
							
							especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('select option:selected').text();
						} else if(
							$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').length != 0 &&
							$(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val() != ""

							) {
							
							especificacion_completado = $(this).parents('.fDetalleInterv-list-item').next().find('input.input-nuevo').val();
						}

						if(
							$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').length != 0 &&
							$(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val() != ""

						) {
						
						repuestos_completado = $(this).parents('.fDetalleInterv-list-item').next().next().next().find('input.input-nuevo').val();
						} 

						detalles_completados[contador_completados] = [];

						detalles_completados[contador_completados]['reparacion'] = accion_completado;
						detalles_completados[contador_completados]['reparacion'] += " " +elemento_completado;

						if(especificacion_completado != "") {
							detalles_completados[contador_completados]['reparacion'] += " " + especificacion_completado;						
						}

						detalles_completados[contador_completados]['repuestos'] = repuestos_completado;
						
						contador_completados++;
					}
					
				} else if (
					!$(this).parents('.fDetalleInterv-list-item').find('input.input-nuevo').length
				) {
					
					// No hay input de nuevo elemento
					if(nuevoDetalle) {

						linea_incompleta = true;
						
						$(this).classToContainer('input-warning', 'a');

						
						errores_detalle[i]['posicion'] = i + 1;
						errores_detalle[i]['tipo'] = $(this);
						errores_detalle[i]['msg'] = 'Campo de elemento';
					}

				}
				
			} 
		});
		
		out['linea_incompleta'] = linea_incompleta;
		out['errores_detalle'] = errores_detalle;
		out['detalles_completados'] = detalles_completados;

		return out;
	
	}

	$.hay_elementos_seleccionados = function() {	
		var hay_seleccionados = false;
		$.each($('.campo-elemento'), function(i,v){
			if(($(v).val() != 0 && $(v).next('input').length == 0) || ($(v).next('input').length != 0 && $(v).next('input').val() != "")) {
				hay_seleccionados = true;
				return hay_seleccionados;
			}
		});
		return hay_seleccionados;
	}

	$.hay_errores_detalle = function() {	

		var validador_detalle = $.validar_detalle(false);
		var hay_errores = false;
		$.each(validador_detalle['errores_detalle'], function(i,v){
			if(validador_detalle['errores_detalle'][i]['posicion']){
				hay_errores = true;
				return hay_errores;
			}
		});

		return hay_errores;
	}


})(jQuery, window)


