;(function($, window, undefined) {
// Repairs constructor
	var Search = function(elem, options) {
		this.elem = elem;
		this.$elem = $(elem);

		if(this.init) {
			this.init(options);
		}
	}

	Repairs.prototype = {

		defaults : {
			repairsInput : "#search",


		},

		init : function(options) {
			this.config = $.extend({}, this.defaults, options);

			this.$initField = this.$elem.eq(0);
			this.$container = this.$elem.eq(0).parent();
			
			
			that = this;
			
			// Show fields when click initField.

			$(this.config.repairsInput).focus(function(){
	
				that.showForm();
				

				// EVENT FIELDS FOCUS 
				$(".sugg-field").focus(function(e) { 
			
					// Get field and box suggestións.

					that.$field = $(this);
					that.$boxSugg = that.$field.parent().find('div.suggestionBox');	
				

					// EVENT FIELDS KEYUP 
					that.$field.keyup(function(e){	
				
						// EVENT FIELD KEYDOWN - Disable default actions	

						
						that.$field.keydown(function(e) {

							if(
								e.keyCode == 38                   || // up-arrow key
								e.keyCode == 40                   || // down-arrow key
								//(e.keyCode == 9 && !e.shiftKey)   || // tab key
							    // (e.keyCode == 9 &&  e.shiftKey)   || // tab key + SHIFT
								e.keyCode == 13                    // enter key
							){
								
						        e.preventDefault();
						   	
						   	}

						});
					
						// if the key is a letter, to search suggestions

						if(
							e.keyCode != 27 &&  // esc key
							e.keyCode != 38 &&  // up-arrow key
							e.keyCode != 40 &&  // down-arrow key
							e.keyCode != 9  &&  // tab key
							e.keyCode != 13  && // enter key
							e.keyCode != 8      // delete key
						){
							
							that.search(that.$field.val(), that.$field);	

						} else {
						
							/* ESC */         if (e.keyCode == 27) { that.hideBoxSugg(); }	

							// /* Tab + shift */ if (e.keyCode == 9 && e.shiftKey) { that.prevField();   }

							/* DOWN ARROW */  if (e.keyCode == 40) { 
													if (that.$boxSugg) {	
														if (selected = that.getSuggSelected(true)){
															that.nextSugg(selected);
														}
													}			
											  }
							
							/* UP ARROW */    if (e.keyCode == 38) {  
											
												if(that.$boxSugg) {	
													if (selected = that.getSuggSelected()){
														that.prevSugg(selected);
													}
												}
											  }
								
							/* ENTER */       if (e.keyCode == 13)  { 
													
													if (that.$boxSugg) {
														selected = that.getSuggSelected();

														if(selected) {														
															that.addTag($(selected).text());
															that.nextField();

														} else if(that.$boxSugg.find('li') && that.$field.val()) {
															
															that.addTag(that.$field.val());
															that.nextField();
														}
													}
											  }
							
							/* DELETE */      if (e.keyCode == 8) {  

													if(that.hasTags()) {
														tag = that.getLastTag();
														that.removeTag(tag);
											  		}	
											}
							
							// /* TAB */ 	    if (e.keyCode == 9 && !e.shiftKey) {
														
											
							// 					if(that.$boxSugg) {
													
							// 						if(that.hasSugg()){	
														
							// 							selected = that.getSuggSelected();

							// 							sugg = selected ? $(selected).find('a') : that.getFirstSugg();
							// 							that.addTag(sugg.text().trim());
							// 							that.nextField();

							// 						} else if (that.$field.val()) {
							// 							that.addTag(that.$field.val());
							// 							that.nextField();

							// 						} else if (!that.hasSugg() && !that.$field.val()){

							// 							console.log(that.$field);
							// 						} 
													
							// 					} 	
												
							// 				}	
						}

					});

					that.$boxSugg.mousemove(function() {
						$(this).find('li.sugg-true').mousemove(function() {

							that.selectSugg(this);
						});
					});

					that.$boxSugg.mousemove(function() {
						that.$boxSugg.show();
						$(this).find('a').click(function(e) {

							e.preventDefault();

							that.addTag($(this).text().trim());
							that.nextField();

						
						});
					});

					that.$boxSugg.mouseout(function() {
						if(selected = that.getSuggSelected()) {
							$(selected).removeClass('selected');
						}


					});

					// EVENT FIELD BLUR - Hide box suggestions	  

					that.$field.blur(function(e){
						e.preventDefault();
						selected = that.getSuggSelected();

						if(selected){
							that.addTag($(selected).text().trim());
							that.nextField();
						} else {
							that.hideBoxSugg();
						}

					
					});

					$('a.remove-tag').click(function(e) {
						e.preventDefault();
						
						tag = $(this).parent().parent().parent();
						that.removeTag(tag);
					});

				});	
				
			});
		},
		
		// Suggs method

		getFirstSugg : function() {
		
				return that.$boxSugg.find('li a').first();
		},

		getSuggSelected : function(first) {

			var allSugg = that.$boxSugg.find('li');	

			selected = false;
			if (allSugg.length > 0) {
				for (var i = 0; i <= allSugg.length; i++) {
					if($(allSugg[i]).hasClass('selected')) {
						selected = allSugg[i];
					
					} 
				}
	
				if (!selected && first) {
						selectedFirst = that.$boxSugg.find('li').first();
						selectedFirst.addClass('selected');
				} 
			}

			return selected;
			
			

		},

		nextSugg : function(selected) {
			totalSugg = $(selected).parent().children().length;
			allSugg = $(selected).parent().children();
			
			
			for (var i = 0; i < totalSugg; i++) {
				if($(allSugg[i]).hasClass('selected')){
					
					$(allSugg[i]).removeClass('selected');
					$(allSugg[i+1]).addClass('selected');
					break;
				}
			};

		},

		prevSugg : function(selected) {
			totalSugg = $(selected).parent().children().length;
			allSugg = $(selected).parent().children();
			
			for (var i = 0; i < totalSugg; i++) {
				if($(allSugg[i]).hasClass('selected')){
					
					$(allSugg[i]).removeClass('selected');
					$(allSugg[i-1]).addClass('selected');
					break;
				}
			};
			
			

		},

		selectSugg : function(sugg) {
			selected = that.getSuggSelected();
			if(selected) {
				$(selected).removeClass('selected');
				$(sugg).addClass('selected');
			} else {
				$(sugg).addClass('selected');
			}

		},


		hasSugg : function() {
			totalSugg =  that.$boxSugg.find('li.sugg-true a');
			return totalSugg.length > 0 ? true : false;	
		},

		hideBoxSugg : function() {
			 that.$boxSugg.empty();
		},

		// Tags method

		addTag : function(text) {
			
			// field = !prev ? that.$field : that.$field.parent().prev().find('input');
			

			that.$field.parent().append($(
								"<div class='content-float-tags'>" +
									"<div class='content-tags'>" +
										"<div class='single-tag'>" +
											"<span class='text-tag'>" + text.trim() + "</span>" +
											"<a href='#'' class='remove-tag'></a>" +
										"</div>" +
									"</div>" +
								"</div>"
								));
			
			

			nextField = that.$field.parent().next().find(":input");
			that.$field.removeAttr('placeholder');
			that.$field.val("");
			incremento = that.$field.parent().find("div.single-tag").outerWidth() + 7;
			that.$field.attr('maxlength',0);
			that.$field.css({ paddingLeft : incremento });

			that.hideBoxSugg();
			



		},
		hasTags : function() {
			tag = that.$field.parent().find('div.content-float-tags');
			return (tag.length > 0) ? true : false;
		},

		getLastTag : function() {
			return that.$field.parent().find('div.content-float-tags');

		},

		removeTag : function(tag) {
			that.$field = tag.parent().find(':input');
			idField = that.$field.attr('id');

			switch (idField) {

				case 'action':
					that.$field.attr('placeholder', 'Acción');
				break;
				case 'object':
					that.$field.attr('placeholder', 'Objeto');
				break;
				case 'location':
					that.$field.attr('placeholder', 'Localización');
				break;
				case 'parts':
					that.$field.attr('placeholder', 'Repuestos');
				break;

			}

			tag.empty();
			tag.addClass('suggestionBox');
			that.$field.css({ paddingLeft : "5px" });
			
			that.$field.removeAttr('maxlength');
			that.$field.focus();

			$(boxSugg).remove();

		},

		// Fields methods

		nextField : function() {

			nextField = that.$field.parent().next().find(":input");
			nextField.focus();
			$(boxSugg).remove();

		},
		prevField : function() {

			prevField = that.$field.parent().prev().find(":input");
			prevField.focus();
			$(boxSugg).remove();

		},

		// Other methods

		search : function(text) {
			text = text.trim();
			text = encodeURI(text);

			if(text.length > 0){

				$.ajax({
				  url: 'public/Modules/Repairs/search.php',
				  type: 'POST',
				  async: true,
				  data: 'text=' + text,
				  success: function(responseText){
				  		that.$field.parent().children().html(responseText);
						that.$field.parent().children().show();
						that.$suggs = that.$boxSugg.find('li');
				  },
				});
			}		
		},

		showForm : function() {
			
			this.$initField.remove();

			this.$container.hide().prepend($(
				"<ol>" + 
					" <li class='text action'>" + 
						"<input type='text' name='action' id='action' class='sugg-field action' placeholder='Acción' />" +
						"<div class='suggestionBox'></div>" +
					"</li>" +

					"<li class='text object'> " + 
						"<input type='text' name='object' id='object' class='sugg-field object' placeholder='Objeto' /> " + 
						"<div class='suggestionBox'></div>" +
					"</li>" +

					"<li class='text location'>" +
						"<input type='text' name='location' id='location' class='sugg-field location' placeholder='Localización'/>" +
						"<div class='suggestionBox'></div>" +
					"</li>" +

					"<li class='parts'> " +
						"<textarea name='parts' id='parts' class='sugg-field parts' placeholder='Repuestos'></textarea>" +
						"<div class='suggestionBox'></div>" +

					"</li>" +

					"<li class='resumenRepairs'>" +
						"<textarea name='allRepairs' id='allRepairs' class='allRepairs' placeholder='Resumen de reparación'></textarea>" + 
					"</li>" +

					"<li class='observations'>" + 
						"<input name='observations' id='observations' class='observations' placeholder='Observaciones'/>" +
					"</li>" +
				"</ol>" 	
			)).slideDown( "fast");
		},

	
	}

	$.fn.repairFields = function(options) {
		if(typeof options == "string") {
			method = options;
			args = Array.prototype.slice.call(arguments, 1);
			
			var repairs = this.data('repairs') ?
				this.data('repairs') : 
			new Repairs(this);

			if(repairs[method]) {
				repairs[method].apply(repairs, args);
		
			}
		} else if (typeof options == "object" || !options) {
			this.data('repairs', new Repairs(this, options));
		} else {
			$.error("Error: El parámetro pasado es incorrecto.");
		}

		return this;
	}


	window.Repairs = Repairs;
})(jQuery, window)