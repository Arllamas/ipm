;(function($, window, undefined) {

//Active buttons

$('.select-place-results-link').click(function(){
  
  $.activarAcciones($(this).text(), $(this).data('id'));
  
  return false;
  
});



})(jQuery, window)