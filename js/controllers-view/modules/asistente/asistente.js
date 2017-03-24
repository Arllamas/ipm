;(function ($) {
    var oAddClass = $.fn.addClass;
    $.fn.addClass = function () {
        for (var i in arguments) {
            var arg = arguments[i];
            if ( !! (arg && arg.constructor && arg.call && arg.apply)) {
                setTimeout(arg.bind(this));
                delete arguments[i];
            }
        }
        return oAddClass.apply(this, arguments);
    }

})(jQuery);
;(function ($) {
    var oRemoveClass = $.fn.removeClass;
    $.fn.removeClass = function () {
        for (var i in arguments) {
            var arg = arguments[i];
            if ( !! (arg && arg.constructor && arg.call && arg.apply)) {
                setTimeout(arg.bind(this));
                delete arguments[i];
            }
        }
        return oRemoveClass.apply(this, arguments);
    }

})(jQuery);

;(function ($) {
    var oCss = $.fn.css;
    $.fn.css = function () {
        for (var i in arguments) {
            var arg = arguments[i];
            if ( !! (arg && arg.constructor && arg.call && arg.apply)) {
                setTimeout(arg.bind(this));
                delete arguments[i];
            }
        }
        return oCss.apply(this, arguments);
    }

})(jQuery);

;(function ($) {
    var oAnimate = $.fn.animate;
    $.fn.animate = function () {
        for (var i in arguments) {
            var arg = arguments[i];
            if ( !! (arg && arg.constructor && arg.call && arg.apply)) {
                setTimeout(arg.bind(this));
                delete arguments[i];
            }
        }
        return oAnimate.apply(this, arguments);
    }

})(jQuery);


$.add_contenedor_detalle = function(etiqueta) {
   etiqueta.parent().append('<div class="results">ss<div>');
   etiqueta.parent().find('.results').hide();
}
