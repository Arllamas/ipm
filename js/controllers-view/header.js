;(function($, window, undefined) {


$('.main-nav-button-dropdown.icon-menu').click(function() {
    $('.main-nav').addClass('desplegado');
    $(this).next().show();
    $(this).hide();

    $('.main-nav').prepend("<h3 class='main-nav-title'><span class='main-nav-title-icon icon-menu'></span>MENU</h3>");

    $('.main-nav').append("<li class='main-nav-item'><a href='logout.php' class='main-nav-link exit'>SALIR DE IPMOTOR</a></li>");
});

$('.main-nav-button-dropdown.icon_close').click(function() {
    $('.main-nav').removeClass('desplegado');
    $(this).prev().show();
    $('.main-nav-title').remove();
    $('.main-nav-item .exit').remove();
    $(this).hide();
});


$(window).resize(function() {

  if($(window).width() > 752) {
     $('.main-nav').removeClass('desplegado');
     $('.main-nav-title').remove();
     $('.main-nav-link.exit').parent().remove();
     $('.main-nav-button-dropdown.icon_close').hide();
  } else if(!$('.main-nav').hasClass('desplegado')){
    $('.main-nav-button-dropdown.icon-menu').show();
  }

});







// return true o false width dropdown state
var hasActive = function() {
  return $('.user-area-header').hasClass('is-active') ? true : false;
};

// Active and desactive actions
var active = function() {
      $('.user-area-outfocus-dropdown').addClass("is-active");
      $('.user-area').addClass("is-active");
      $('.user-area-header').addClass("is-active");
       $('.user-area-header').addClass("transition");
    };
var desactive = function() {
      $('.user-area-outfocus-dropdown').removeClass("is-active");
      $('.user-area').removeClass("is-active");
      $('.user-area-header').removeClass("is-active");
    };

// Add css width ti user-area-header to can to do animations with it. 



  //  var fWidth = parseInt($('.user-area-header').css('width'));
  // fWidth = fWidth + 1 + "px";

  //    $('.user-area-header').css( "width", fWidth);




// Event close dropdown 
$('.user-area-outfocus-dropdown').click(function() {
    desactive();
    // alert("DESACTIVAR");
});

// Event open dropdown
$('.user-area-header').click(function(){
  //if dropdown opened
    if(hasActive()) {
       desactive();
       // alert("DESACTIVAR");
    } else {
      active();
      // alert("ACTIVADO");
    }

});

})(jQuery, window)