;(function($, window, undefined) {

  var search = function(text, field) {

    text = text.trim();
      contentResults = field.parent().find('.consult-record-results'); 

          $.ajax({
            url: 'public/footer/content-blocks/search.php',
            type: 'POST',
            async: true,
            data: 'text=' + text,
            success: function(responseText){

               $('.consult-record-results').html(responseText);
               

            },
          });
          
  };

  // Select input.val() when focus 

  $(".select-place-input").focus(function(){  
    $('.input-field').removeClass("is-active");  
    this.select();
  });
  $(".select-place-input").mouseup(function(e){

        e.preventDefault();
});

  // Ajax request to show suggestions 

      //Detect android user
      var ua = navigator.userAgent.toLowerCase();
      var isAndroid = ua.indexOf("android") > -1; 

      //Change numeric/text keyboard to enter car registration at history finder

      if(isAndroid) {
        $(".consult-record-input").focus(function(){  
          $(this).get(0).type = "text";  
        });
      }


      $(".consult-record-input").keyup(function() {
        if($(this).val().length <= 3 && $(this).get(0).type != "tel") {
         
          if(!isAndroid) {
            $(this).get(0).type = "tel";     
            $(this).get(0).blur();
            $(this).get(0).focus();
            $('html, body').animate({scrollTop:$(document).height()}, 'slow');

          } else {
            $(this).get(0).type = "text";  
          }
        } 
        if($(this).val().length >= 4 && $(this).get(0).type != "text") {
        
          if(!isAndroid) {
            $(this).get(0).type = "text";     
            $(this).get(0).blur();
            $(this).get(0).focus();
            $('html, body').animate({scrollTop:$(document).height()}, 'slow');

          } else {
            $(this).get(0).type = "text"; 
          }

        } 
    
    search($(this).val(), $(this));

  });



  // Disable default buttons

  $(".select-action-button").removeAttr('href');



})(jQuery, window)