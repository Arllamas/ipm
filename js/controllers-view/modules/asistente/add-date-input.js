;(function($, window, undefined) {

$.seleccionar_deseleccionar_input_date = function (input) {

  input.parent().find('.reg-item-tipo-validate-input-icon').toggleClass('active');
  input.parent().find('.reg-item-tipo-validate-input-label').toggleClass('active');
}
var search_fecha_programada = function (input) {


  var idplanificacion = input.parent().parent().parent().find('.reg-item-tipo-preventivo').data('idplanificacion');
  var campo_fecha = input;

  $.ajax({
    url: 'public/modules/asistente/search_fecha_programada.php',
    type: 'POST',
    async: true,
    data: 'idplanificacion=' + idplanificacion,
    success: function(responseText){
    // alert(responseText);
    if(parseInt(responseText) == 2) {

        $.input_error(campo_fecha);

      } else {

   
        campo_fecha.val(responseText);
      }          
    },
  });
}

$.input_success = function (input) {

  $('.alert').remove();
     
      var container = input.parent().parent().find('.reg-item-tipo-validate-input-container');
      var label = input.parent().parent().find('.reg-item-tipo-validate-input-label');
      var icon = input.parent().parent().find('.reg-item-tipo-validate-input-icon');

     
        label.text('PLANIFICADO');
        container.addClass('planificado');
        label.addClass('planificado');
        icon.addClass('icon3-calendar-check-o').addClass('planificado');


}
$.input_error = function(input) {

   $('.alert').remove();
     
      var container = input.parent().parent().find('.reg-item-tipo-validate-input-container');
      var label = input.parent().parent().find('.reg-item-tipo-validate-input-label');
      var icon = input.parent().parent().find('.reg-item-tipo-validate-input-icon');

      if(container.hasClass('planificado')) {

       
         container.addClass('error');
        label.addClass('error');
        icon.removeClass('icon3-calendar-check-o').addClass('error').addClass('icon3-calendar-times-o');
        function remove_class_error() {
          
          label.removeClass('error').addClass('planificado');
          container.removeClass('error').addClass('planificado');
          icon.removeClass('error').addClass('planificado').addClass('icon3-calendar-check-o');
          search_fecha_programada(input);
          
        }

        setTimeout(remove_class_error,3400);


        

      } else {


     
        container.addClass('error');
        label.addClass('error');
        icon.removeClass('icon3-calendar-check-o').addClass('error').addClass('icon3-calendar-times-o');

         function remove_class_error() {
          
          label.removeClass('error');
          container.removeClass('error');
          icon.removeClass('error').addClass('icon3-calendar-check-o');
          input.val('');
          
        }

        setTimeout(remove_class_error,3400);



      } 


      $('body').append('<div class="alert">');
        $('.alert').hide(); // Ocultar de momento
        $('.alert').append('<li style="list-style: none !important;" class="alert-list-item">Introduce una fecha de este mes en formato válido</li></ul>'); 
        $('.alert').addClass('error');
      
      // Posicionar alerta
      $(window).resize(function () {
        $(".alert").css({
            zIndex: 3,
            position: 'fixed',
            // Se alinea en la horizontal cogiendo medidas del navegador 
            left: ($(window).width() - $('.alert').outerWidth()) / 2,
            top: ($(window).height() - $('.alert').outerHeight() - 20)

        });

        
      });
    // El metodo .delay establece un tiempo de espera entre el fadeIn y fadeOut
    // Este delay varía en función de las lineas que tenga la alerta 5000 * nº lineas
    $(".alert").fadeIn(500, function(){
      $('.alert').delay(3000 * $('.alert-list-item').size())
      .fadeOut(500);
    }); 
      // Ejecutar la función que muestra y oculta las alertas
      $(window).resize(); 
}


$.add_input_date = function(content, tipo) {

  


  $(".fecha-plan").focus(function(){
            $.seleccionar_deseleccionar_input_date($(this));
	});




  

        
$(".fecha-plan").bind("blur", function(){
   
	    $.seleccionar_deseleccionar_input_date($(this));


     
     var idplanificacion = $(this).parent().parent().parent().find('.reg-item-tipo-preventivo').data('idplanificacion');

    if($(this).val()) {

      var nueva_fecha = $(this).val();

      var campo_fecha = $(this);
     $.ajax({
        url: 'public/modules/asistente/set_fecha_programada_preventivo.php',
        type: 'POST',
        async: true,
        data: 'FechaProgramada=' + nueva_fecha + '&idplanificacion=' +idplanificacion,
        success: function(responseText){
             // alert(responseText);
            if(parseInt(responseText) == 2) {

              $.input_error(campo_fecha);
            } else {
              $.input_success(campo_fecha);
              campo_fecha.parent().parent().parent().find('.results > span').fadeOut();
            }
            
           
          
        },
      });




    } else {
      if($(this).parent().parent().parent().find('.planificado').hasClass('planificado')) {
        $.input_error($(this)); 
      }
     
    }
      
    });
   
}



 	



})(jQuery, window)