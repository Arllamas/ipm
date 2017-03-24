;(function($, window, undefined) {

$.cerrar_etiqueta_preventivo = function (etiqueta) {

    etiqueta.removeClass('tag-selected');
    etiqueta.parent().find('.tag-hidden').fadeIn().removeClass('tag-hidden');

    etiqueta.css("margin-right", '100%').animate({
          "margin-right": "0px"}, 500);   
}


$.abrir_etiqueta_preventivo = function(etiqueta) {

      etiqueta.parent().find('span').not('.tag-selected').addClass('tag-hidden').hide();

      etiqueta.css("margin-right", '64px').animate({
        "margin-right": "+=100%",
        opacity: 1
      }, 500);

}

$.buscar_detalle_preventivo = function(etiqueta) {


       idplanificacion = etiqueta.data('idplanificacion');
       contentResults = etiqueta.parent().find('.results'); 
        

          $.ajax({
            url: 'public/modules/asistente/search_resumen_preventivo.php',
            type: 'POST',
            async: true,
            data: 'idplanificacion=' + idplanificacion,
            success: function(responseText){
               // $.desactivarAcciones();
               contentResults.html(responseText);

               $.add_input_date(contentResults, 'preventivo');
              

               function aparecer() {
                contentResults.fadeIn();
               }

               setTimeout(aparecer,300);
               
            },
          });


}

  
})(jQuery, window)