function Scroll(id, speed) {
//establezco velocidad y cojo la capa con mi contenido
	this.speed = speed;
	this.oDiv = document.getElementById(id);
	this.content = this.oDiv.innerHTML;
//establezco una segunda capa con el mismo contenido	
	this.scroll_layer = "<div id='scroller_" + id + "' style='position: relative;'>" + this.content + "</div>";
	this.oDiv.innerHTML = this.scroll_layer;
	this.oScroll = document.getElementById('scroller_' + id);
	this.oScroll.style.height = this.oDiv.offsetHeight + "px";
	this.start();
	
//cambio para q se mueva al pasar x encima
	
	self = this;
	this.oDiv.onmouseover = function() {
		self.resume();
	};
	this.oDiv.onmouseout = function() {
		self.pause();
	}
}

Scroll.prototype.paused = false;

Scroll.prototype.oDiv = new Object();
Scroll.prototype.oScroll = new Object();

Scroll.prototype.content = "";
Scroll.prototype.scroll_layer = "";
//cambio mask_height x mask_width
Scroll.prototype.mask_width = 0;

Scroll.prototype.speed = 1;

Scroll.prototype.start = function() {
//lo cambio para que al principio este pausado
	this.paused = true;
//pongo q el ancho d la mascara sea igual al de la capa
	this.mask_width = this.oDiv.offsetWidth;
	
	this.oScroll.style.position = 'relative';
	//cambio style top por style left
	// con esto, el scroll empieza "después" de la máscara.
	//this.oScroll.style.left = this.mask_width + "px";
	this.oScroll.style.left = 0;
	this.oScroll.style.top = 0;
	
	this.scroll();
};

Scroll.prototype.pause = function() {
	this.paused = true;
};

Scroll.prototype.resume = function() {
	this.paused = false;
};

//no se lo q he hecho. cambio le top por left y los height por width
Scroll.prototype.scroll = function() {
	if (!this.paused) {
		/*
		Esta línea es la que mueve, echando a la izquierda cada vez un
		número de píxeles indicado por this.speed.
		
		style.left establece el left
		offsetLeft toma el left real
		*/
		this.oScroll.style.left = (parseInt(this.oScroll.style.left) - this.speed) + "px";
		if (parseInt(this.oScroll.offsetLeft) <= this.oDiv.offsetLeft - this.oScroll.offsetWidth) {
			this.oScroll.style.left = this.mask_width + "px";
		}
	}
	
	/*
	Ese 30 se puede cambiar y quiere decir que se ejecuta _self.scroll()
	cada 30 milisegundos.
	*/
	var _self = this;
	window.setTimeout(function() {
			_self.scroll();
			}, 30);
};

Scroll.prototype.showContent = function() {
	alert(this.content);
};
