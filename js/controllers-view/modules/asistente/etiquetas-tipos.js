
;(function($, window, undefined) {

$('.reg-item-tipo span').click(function(){

  $(this).parent().find('.results').remove();
  $(this).parent().find('input').remove();
  
  // Si la etiqueta presionada  está seleccionada hay que cerrar

  if($(this).hasClass('tag-selected')) {
    
	  if($(this).hasClass('reg-item-tipo-itv')) {

        $.cerrar_etiqueta_itv($(this)); 
    }

    if($(this).hasClass('reg-item-tipo-preventivo')) {

        $.cerrar_etiqueta_preventivo($(this));
    }

  } else {
  // Si la etiqueta presionada no está activa hay que abrir nuevo contenido

  // Ocultamos todos por si acaso


    // Marcamos como seleccionada la etiqueta

    $(this).addClass('tag-selected'); 
  	
    // Si la etiqueta es para preventivo
    if($(this).hasClass('reg-item-tipo-preventivo')) {

      $.abrir_etiqueta_preventivo($(this));
      $.add_contenedor_detalle($(this));
      $.buscar_detalle_preventivo($(this));


    
          
      

    }


      // Si la etiqueta es de ITV
     if($(this).hasClass('reg-item-tipo-itv')) {

      $.abrir_etiqueta_itv($(this));
      $.add_contenedor_detalle($(this));
      $.buscar_detalle_itv($(this));

    }
    
  	  
  }
		
 	
  
});

})(jQuery, window)