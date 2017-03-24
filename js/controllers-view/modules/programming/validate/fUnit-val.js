;(function($, window, undefined) {

// Evento al pulsar boton 'Grabar actuaciones' del form
$('#btnw').click(function(){

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
	$.formatDate = function(date) {

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

		// Componer la fecha en el formato deseado	
		var fechaFormateada = diaSemana + " " + dia + " de " + mes + " del " + anno; 

		return fechaFormateada;
	}

	// Función que muestra la ventana modal con los datos rellenados en el formulario
	// Pasar como parametro array completed
	$.showModal = function(arrayCompleted) {

		// Crear ventana modal con el resumen para confirmación del formulario
		// Vaciar (empty) ventana modal cada vez que se muestra
		$('body').append('<div id="dialog" title="'+ $(".second-nav-breadcrumb").text() +' - Resumen de actuaciones programadas">').find('#dialog').empty();

		// Crear contenido ventana modal
		// Comprobar si hay avisos
		if(arrayCompleted['avisos']) {

			// Obtener numero de matrículas avisadas
			var nMatriculas = 0;
			$.each(arrayCompleted['avisos'], function(i,v){
				if(v) { nMatriculas++; }
			});
			
			// Si hay matrículas
			if (nMatriculas > 0) {
				$('#dialog').append('<h4>Aviso</h4>');
				// Obtener fecha aviso formateada
				var fechaAviso = $.formatDate($('#dt-notice').val());
				// Añadir Fecha aviso a la ventana modal
				$('#dialog').append('<li><strong>Fecha del aviso:</strong> ' + fechaAviso + '</li>');
				
				// Añadir nº matriculas a la ventana modal
				$('#dialog').append('<li><strong>Vehículos:</strong> ' + nMatriculas + '</li>');
				

				// Crear lista para matriculas del aviso
				$('#dialog').append('<li class="dialog-list-itemMat">');
				$('.dialog-list-itemMat').append('<ul class="dialog-listMat">');
				// Seleccionar todos los select de matrícula (avisos) con matricula seleccionada
				$('.select-mat>option:selected').not('[value=0]').each(function(i,v){
					// Añadir parte de avería por matrícula avisada
					var mat = $(v).text();
					var par = $(v).findFromParent('.fIU-list-item', 'textarea',true).val();

					$('.dialog-listMat').append('<li><strong>'+ mat +':</strong> ' + par + '</li>');
					
				});
			}

		}

		// Si hay preventivos o lavados
		if(arrayCompleted['prevlav']){
			$('#dialog').append('<h4>Preventivos y Lavados</h4>');
			var prev =  $.getMultiselectValues('prevAndwash', 'P', 'string');
			var lav = $.getMultiselectValues('prevAndwash', 'L', 'string');

			if(prev) {
				$('#dialog').append('<li><strong>Preventivos:</strong> ' + prev + '</li>');
			}

			if(lav) {
				$('#dialog').append('<li><strong>Lavados:</strong> ' + lav + '</li>');
			}
		}

		if(arrayCompleted['itv']){
			$('#dialog').append('<h4>ITV</h4>');
			var itv =  $.getMultiselectValues('matITV', false, 'string');
	
			if(itv) {
				$('#dialog').append('<li><strong>Fecha cita:</strong> ' + $.formatDate($('#datetimeITV').val()) + '</li>');
				$('#dialog').append('<li><strong>Matrículas:</strong> ' + itv + '</li>');
			}
		}

		$('#dialog').wrapInner('<ul class="dialog-list">')
		.find('li').addClass('dialog-list-item')
		.end().find('h4').addClass('dialog-list-title');

	      		
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
					text: "Confirmar programación",
			        class: 'dialog-confirm-btm', 
			        click: function() { 
			            $(this).dialog("close");
			            // Volver a permitir scroll en body
			            $('body').css('overflow','auto');
						$('body').css('position','static');
						// Procesar formulario en servidor
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
			$("#dialog").dialog( "option", "maxHeight", $(window).height() * 0.7);
		});

		// Mostrar ventana modal
		$('#dialog').dialog('open');

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
					out.push($(this).val());		
				}
			}
		});

		if(type == "array") {
			return out;
		} else if( type == "string") {
			var string_out = "";
			$.each(out, function(i,v) {
				string_out += v + ", ";
			});
			string_out = string_out.substring(0, string_out.length-2);
			return string_out;
		}
		
	}

	// Función para comprobar si hay errores o bloques completados en el formulario
	// Comprobar errores -> array = errors
	// Comprobar bloques completados -> array = completed
	$.hasEC = function(array) {

		// Si hay errores en la fecha aviso paramos la función y devolvemos true
		// La fecha de aviso siempre tiene completed false
		if (array['date']) { return true; }
		
		// Si hay errores o avisos completados paramos la función y devolvemos true
		// Se usa esta variable porque poder devolver true ya que con return dentro del bucle salimos de éste pero no de la función.
		var avisoEC;
		
		$.each(array['avisos'], function(i, v){		
			if (v) { avisoEC = true; }
		});	
		
		if(avisoEC) { return true; }
		
		// Si está completed el campo de preventivos y lavados devolvemos true
		// Este campo siempre tiene errors false
		if (array['prevlav']) { return true; }

		// Si hay errores o esta completado el bloque de ITV devolvemos true
		if (array['itv']) { return true; }

		// Si la ejecución llega a esta linea es que no hay errores o bloques completados (según el array de entrada)
		return false;
		
	}


	// Función para crear alertas error o warning
	$.printMensajes = function() {
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
			$.each(errors['avisos'], function(i, v){		
				if (v) { 
					$('.alert').append('<li class="alert-list-item">' + v + '</li>');
					return false;
				}
			});	
			if (errors['itv']) { 
				$('.alert').append('<li class="alert-list-item">' + errors['itv'] + '</li>'); 
			}

			$('.alert').wrapInner('<ul class="alert-list">');
		
		// Si no hay errores ni bloques completados rellenar el contenedor con warning	
		} else if(
			!$.hasEC(errors) && 
			!$.hasEC(completed)
		){
			$('.alert').addClass('warning');
			$('.alert').append('<li class="alert-list-item">Completa almenos un bloque del formulario para grabar (Avisos, Preventivos, Lavados o ITV)</li>')
			.wrapInner('<ul class="alert-list">');
		}	

		// Función que muestra las alertas y oculta con transición de fundido
		$(window).resize(function () {
				$(".alert").css({
						zIndex: 3,
						position: 'fixed',
						// Se alinea en la horizontal cogiendo medidas del navegador 
						left: ($(window).width() - $('.alert').outerWidth()) / 2,
						top: ($(window).height() - $('.alert').outerHeight() - 20)

				});

				// El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
				// Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
				$(".alert").fadeIn(500, function(){
					$('.alert').delay(5000 * $('.alert-list-item').size())
					.fadeOut(500);
				});
			});
			
			// Ejecutar la función que muestra y oculta las alertas
			$(window).resize();		
	}


	// Array que almacena los bloques cumplimentados correctamente.
	// (![date], ["avisos"], ["prevlav"] e ["itv"])
	var completed = new Array();
	completed['avisos'] = []; // Inicializar completed['avisos'] como array de arrays
	// Array que almacena los errores al cumplimentar distintos bloques.
	// ([date], ["avisos"], !["prevlav"] e ["itv"])
	var errors = new Array();
	errors['avisos'] = []; // Inicializar errors['avisos'] como array de arrays

	// VALIDACIÓN DATE (Fecha y hora avisos)
	// Obtener la Fecha aviso y fecha máxima
	var dateNotice = $('#dt-notice').val().replace('-','/');		
	var maxdateNotice = $('#dt-notice').attr('max').replace('-','/');

	// Si la fecha de aviso está bien cumplimentada y es menor o igual a la fecha máxima
	if($('#dt-notice').val() && (dateNotice <=  maxdateNotice)) {
		// Quitar error en campo dt-notice y actualizar error[] y completed[]
		$('#dt-notice').classToContainer('input-error', 'r');
		
		errors["date"] = false;
		// El campo fecha aviso no completa un bloque de formulario por si solo
		// Los bloques necesarios para completar un formulario son (Avisos || prevlav || itv )
		completed["date"] = false;
	} else {
		// Mostrar error en campo dt-notice y actualizar error[] y completed[]
		$('#dt-notice').classToContainer('input-error', 'a');
		
		errors["date"] = "La fecha de aviso debe ser menor a la fecha actual.";

		// Si el campo de fecha aviso está mal cumplimentado
		if (!$('#dt-notice').val()) {
		errors["date"] = "El campo fecha y hora aviso está mal cumplimentado.";
		}
		completed["date"] = false;
	}
	
	// VALIDACIÓN AVISOS
	// Recorrer todos los select de matrícula de "Recoger Aviso"
	$('.select-mat').each(function(){
		// En cada bloque matrícula-descripción
		// Si hay matrícula seleccionada y no descripción
		if(
			$(this).find('option:selected').val() != 0 && 
			$(this).findFromParent('.fUnit-item-reg', 'textarea', true).val() == ""
		) {
				// Mostrar error en textarea y actualizar error[] y completed[]
				$(this).classToContainer('input-error', 'r')
				.findFromParent('.fUnit-item-reg', 'textarea', true).classToContainer('input-error', 'a');
				
				errors["avisos"].push("La descripción de avería es un campo obligatorio para recoger el aviso");
				completed['avisos'].push(false);
		// Si no hay matrícula seleccionada y hay descripción
		} else if(
			$(this).find('option:selected').val() == 0 && 
			$(this).findFromParent('.fUnit-item-reg', 'textarea', true).val() != ""
		) {
			
				// No debería darse este caso nunca:
				// al borrarse el  contenido y desactivar textarea cuando deseleccionamos matrícula
				// Repetir acciones por seguridad y actualizar error[] y completed[] 
				
				//Desactivar textarea asociado y borrar contenido
		 		$(this).findFromParent('.fUnit-item-reg', 'textarea', true).prop('disabled', true)
		 		.classToContainer('input-disabled','a')
		 		.val('')
		 		.classToContainer('input-error', 'r');

				errors["avisos"].push(false);
				completed['avisos'].push(false);

		// Si hay matrícula seleccionada y hay descripción
		} else if(
			$(this).find('option:selected').val() != 0 && 
			$(this).findFromParent('.fUnit-item-reg', 'textarea', true).val() != ""
		) {

				// Quitar errores en matrícula y descripción y actualizar error[] y completed[]
				$(this).classToContainer('input-error', 'r')
				.findFromParent('.fUnit-item-reg', 'textarea', true).classToContainer('input-error','r');
				
				completed['avisos'].push(true);
				errors["avisos"].push(false);
		// Si no hay matricula ni descripción
		} else if(
			$(this).find('option:selected').val() == 0 && 
			$(this).findFromParent('.fUnit-item-reg', 'textarea', true).val() == ""
		) {
				// Quitar errores en matrícula y descripción y actualizar error[] y completed[]
				$(this).classToContainer('input-error','r')
				.findFromParent('.fUnit-item-reg', 'textarea', true).classToContainer('input-error', 'r');

				completed['avisos'].push(false);
				errors["avisos"].push(false);

			}
	});
	
	
	// VALIDACIÓN PREVENTIVOS Y LAVADOS
	// Comprobar si hay checkbox activos en Preventivos y Lavados
	
	completed['prevlav'] = $.multiselectActive('prevAndwash');

	
	// VALIDACIÓN ITV
	// Obtener la Fecha y hora de la cita ITV y la fecha mínima
	var dateITV = $('#datetimeITV').val().replace('-','/');		
	var mindateITV = $('#datetimeITV').attr('min').replace('-','/');

	// Si no hay matrículas seleccionadas pero hay fecha de cita bien cumplimentada y es mayor a fecha minima
	if(
		!$.multiselectActive('matITV') &&
		$('#datetimeITV').val() && 
		(dateITV >=  mindateITV)  
	) {
		// Mostrar error en matrícula y actualizar error[] y completed[]
		$('#datetimeITV').classToContainer('input-error', 'r')
		.findFromParent('#fU','#matITV',false).classToContainer('input-error','a');
		
		errors["itv"] = "No se ha seleccionado matrícula para la cita de ITV indicada.";
		completed["itv"] = false;
	
	// Si hay matrículas seleccionadas pero no fecha de cita bien cumplimentada o es menor a fecha minima
	} else if (
		$.multiselectActive('matITV') && 
		(!$('#datetimeITV').val() || (dateITV <  mindateITV))
	) {

		// Mostrar error en fecha cita y acualizar error[] y completed[]
		$('#datetimeITV').classToContainer('input-error', 'a')
		.findFromParent('#fU','#matITV',false).classToContainer('input-error','r');
		
		errors["itv"] = "La cita de la ITV debe ser posterior a la fecha actual.";
			if (!$('#datetimeITV').val()) {
				errors["itv"] = "El campo fecha y hora de la cita ITV está mal cumplimentado.";
			}
		
		completed["itv"] = false;
	
	// Si no hay matrícula seleccionada ni fecha
	} else if (
		!$.multiselectActive('matITV') &&
		$('#datetimeITV').val() && 
		(dateITV >=  mindateITV)  

	) {
		// Quitar errores en ambos matrícula y fecha, y actualizar error[] y completed[]
		$('#datetimeITV').classToContainer('input-error', 'r')
		.findFromParent('#fU','#matITV',false).classToContainer('input-error','r');
		
		errors["itvs"] = false;
		completed["itv"] = false;
	
	// Si hay matrícula seleccionada y fecha
	} else if (
		$.multiselectActive('matITV') &&
		$('#datetimeITV').val() && 
		(dateITV >=  mindateITV)
	){
		// Quitar errores en ambos matrícula y fecha, y actualizar error[] y completed[]
		$('#datetimeITV').classToContainer('input-error', 'r')
		.findFromParent('#fU','#matITV',false).classToContainer('input-error','r');

		errors["itvs"] = false;
		completed["itv"] = true;
	}

	
	// VALIDACION DE BLOQUES COMPLETADOS 
	// Si No hay errores y no hay bloques completados
	if (
		!$.hasEC(errors) && 
		!$.hasEC(completed)
	) {
		// Mostrar warnings en los campos
		$('.select-mat').classToContainer('input-warning', 'a');
		$('.ui-multiselect.ui-widget.ui-state-default.ui-corner-all').classToContainer('input-warning', 'a');

		// Quitar errores campos que lo puedan tener
		$('#datetimeITV').classToContainer('input-error', 'r');

		$.printMensajes(); // Mostrar alertas
	
	// Si hay errores
	} else if(
		$.hasEC(errors)
	) {
		
		// Quitar warnings de los campos
		$('.select-mat').classToContainer('input-warning', 'r');
		$('.ui-multiselect.ui-widget.ui-state-default.ui-corner-all').classToContainer('input-warning', 'r');

		$.printMensajes(); // Mostrar alertas
	
	// Si no hay errores y hay completados
	} else if(
		!$.hasEC(errors) && 
		$.hasEC(completed)
	) {
		
		// Quitar warnings de los campos
		$('.select-mat').classToContainer('input-warning', 'r');
		$('.ui-multiselect.ui-widget.ui-state-default.ui-corner-all').classToContainer('input-warning', 'r');

		$('.alert').hide(); // Ocultar alertas

		// Traer ventana modal
		$.showModal(completed);

	}	

	return false;

});




})(jQuery, window)


