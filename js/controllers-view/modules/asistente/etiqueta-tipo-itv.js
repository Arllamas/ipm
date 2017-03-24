;(function($, window, undefined) {

$.cerrar_etiqueta_itv = function (etiqueta) {

    etiqueta.removeClass('tag-selected');
    etiqueta.parent().prepend(etiqueta.parent().find('.tag-hidden'));
    etiqueta.css("margin-right", '100%').animate({
          "margin-right": "10px"}, 500, function(){  

              function aparecer() {
                 etiqueta.parent().find('.tag-hidden').fadeIn().removeClass('tag-hidden');
                 
              }

              setTimeout(aparecer,300);
    });   
}


$.abrir_etiqueta_itv = function(etiqueta) {

      etiqueta.parent().find('span').not('.tag-selected').addClass('tag-hidden').hide();
       etiqueta.css("margin-right", '10px').animate({
          "margin-right": "+=100%",
          opacity: 1
      }, 500);
   

}

$.buscar_detalle_itv = function(etiqueta) {


       idplanificacion = etiqueta.data('idplanificacion');
       contentResults = etiqueta.parent().find('.results'); 

          $.ajax({
            url: 'public/modules/asistente/search_resumen_itv.php',
            type: 'POST',
            async: true,
            data: 'idplanificacion=' + idplanificacion,
            success: function(responseText){
               // $.desactivarAcciones();
               contentResults.html(responseText);

               function aparecer() {
                contentResults.fadeIn();
               }

               setTimeout(aparecer,300);
               
              
            },
          });

}




  
})(jQuery, window)