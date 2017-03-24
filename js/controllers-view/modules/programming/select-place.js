;(function($, window, undefined) {

// Activar acciones "En Unidad" y "Asistencia en Carretera"

$.activarAcciones = function(unit, id) {
  $('.select-action-item').addClass('is-active');
  $('.select-action-icon').addClass('is-active');
  $('.select-place-input').val(unit);
  $('.select-place-results').hide();
  $('.input-field').addClass("is-active");
  $('.select-place').removeClass("is-active");


  $('.select-action-button.unit').attr("href", "index.php?s=p1&u=" + id).addClass("is-active");  
  $('.select-action-button.assistance').attr("href", "index.php?s=p2&u=" + id).addClass("is-active");

}

$.desactivarAcciones = function() {
  $('.select-action-item').removeClass('is-active');
  $('.select-action-icon').removeClass('is-active');
  $('.input-field').removeClass("is-active");
  $(".select-action-button").removeClass('is-active');
  $(".select-action-button").removeAttr('href');
}



  var search = function(text, field) {

    text = text.trim();
    text = encodeURI(text);
    contentResults = field.parent().parent().find('.select-place-results'); 

          $.ajax({
            url: 'public/modules/programming/search.php',
            type: 'POST',
            async: true,
            data: 'text=' + text,
            success: function(responseText){
               $.desactivarAcciones();
               contentResults.html(responseText);
               
               if(text != "" && responseText){
                  contentResults.show();
               } else {
                 
                  contentResults.hide();
                  
               }
            },
          });
          
  };

  // Select input.val() when focus 

  $(".select-place-input").focus(function(){  
    this.select();
  });
  $(".select-place-input").mouseup(function(e){
        e.preventDefault();
});

  // Ajax request to show suggestions 


  $(".select-place-input").keyup(function() {
    
    search($(this).val(), $(this));

  });

  // Disable default buttons

  $(".select-action-button").removeAttr('href');
 
  

})(jQuery, window)