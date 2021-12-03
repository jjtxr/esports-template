/**
 * Featured Content Slider v2 For webSPELL v4.2.x Gaming/SE
 * 
 * @author HenningK.de
 */

(function($) {
	/**
	 * fc_slider Object
	 * 
	 * @param jQ
	 *            object jQuery Object where the slider is bound to
	 * @param set
	 *            object settings
	 */
	var fc_slider = function(jQ, set) {

		SO = this;

		var settings = {
			interval : 4000, // pause between the transitions
			img_container : $("#fc_slider_img"), // Something like the "frame" you look through
			img_slider : $("#fc_slider_slide"), // the slider - needs to be wide!
			imgs : $("#fc_slider_slide > div"), // image or what else containers - works also with text/html!
			navs : $("#fc_slider_nav > div"), // navigation selector
			caption : $("#fc_slider_caption"), // carries the caption
			pause : $("#fc_slider_pause"), // the pause overlay
			slideshow : 1,	// show as slideshow => auto-change
			slideshow_pause : 1,	// pause slideshow on hover - only needed if previous === 1
			active_class : "active", // the active class for navigation
			start_index : 0, // start at 1 (0) or later?
			img_width : 550, // is normally not needed - but sometimes there is a bug
			anim_time : 600, // Time for transition
			easing : "swing", // try jQuery Easing Addon
			transition : "shovel" // slide_left or fade or shovel
		};

		var vars = {
			cur_index : null,
			cont_width : null,
			timer : null,
			img_count : null,
			in_trans : 0
		};

		// extend the settings
		SO.settings = $.extend(settings, set, {});

		/**
		 * PRIVATE METHODS
		 */

		var highlight_nav = function(index) {
			settings.navs.removeClass(settings.active_class);
			$(settings.navs.get(index)).addClass(settings.active_class);
		}

		var start_slideshow = function() {
			vars.timer = setInterval(function() {
				var next_index = vars.cur_index + 1;
				if (next_index == vars.img_count) {
					next_index = 0;
				}
				SO.go_to(next_index);
			}, settings.interval);
			settings.pause.hide();
		}

		var stop_slideshow = function() {
			settings.pause.show();
			clearInterval(vars.timer);
		}

		/**
		 * TRANSITIONS
		 */

		var transitions = {
			slide_left : {
				init : function() {
					settings.img_slider.css('width',vars.img_count*vars.cont_width);
					settings.imgs.css('float','left');
				},
				trans : function(index, afterw) {
					var img_slider = settings.img_slider;
					var navs = settings.navs;
					if (index !== 0) {
						var margin = vars.cont_width * index;
					} else {
						var margin = 0;
					}
					var margin = margin * -1;

					img_slider.stop().animate( {
						marginLeft : margin + "px"
					}, settings.anim_time, function() {
						afterw();
					});
				}
			},
			fade : {
				init : function() {
					settings.imgs.css( {
						'position' : 'absolute',
						'z-index' : 10,
						'display' : 'none'
					});
				},
				trans : function(index, afterw) {
					var curimg = $(settings.imgs.get(vars.cur_index));
					var nextimg = $(settings.imgs.get(index));

					nextimg.css( {
						'z-index' : 30,
						'display' : 'none'
					});
					curimg.css( {
						'z-index' : 20
					});
					nextimg.fadeIn(settings.anim_time, function() {
						if (typeof vars.cur_index == 'number')
							curimg.hide().css( {
								'z-index' : 10
							});
						afterw();
					});
				}
			},
			shovel : {
				init : function() {
					settings.imgs.css( {
						'position' : 'absolute',
						'z-index' : 10
					});
					settings.img_container.css( {
						'position' : 'relative'
					});
				},
				trans : function(index, afterw) {
					var curimg = $(settings.imgs.get(vars.cur_index));
					var nextimg = $(settings.imgs.get(index));
					nextimg.css( {
						left : vars.cont_width,
						'display' : 'block',
						'z-index' : 30
					});
					curimg.css( {
						'z-index' : 20
					});
					nextimg.animate( {
						left : 0
					}, settings.anim_time, function() {
						if (typeof vars.cur_index == 'number')
							curimg.hide().css( {
								'z-index' : 10
							});
						afterw();
					});
				}
			}
		};

		/**
		 * PRIVILEGED METHODS
		 */

		this.go_to = function(index) {
			if (index === vars.cur_index || vars.in_trans)
				return;

			vars.in_trans = 1;
			transitions[settings.transition].trans(index, function() {
				vars.cur_index = index;
				vars.in_trans = 0;
			});

			var cap = settings.caption;
			var capbottom = cap.outerHeight() * -1;
			var atime = settings.anim_time / 2;
			cap.animate( {
				bottom : capbottom + "px"
			}, atime, function() {
				var txt = $(settings.imgs.get(index)).find("span").html();
				cap.html(txt);
				cap.animate( {
					bottom : 0
				}, atime);
			});

			highlight_nav(index);
		};

		/**
		 * INITIALIZER
		 */

		var init = function() {
			if (typeof transitions[settings.transition] != "object")
				return false;

			vars.cont_width = settings.img_container.width() || settings.img_width;
			vars.cur_index = settings.start_index - 1 || 0;
			vars.img_count = settings.imgs.length;
			$.each(settings.imgs, function() {
				$(this).width(vars.cont_width);
			});

			settings.navs.click(function() {
				SO.go_to($(this).index());
			});

			if (typeof transitions[settings.transition].init == "function") {
				transitions[settings.transition].init();
			}

			SO.go_to(settings.start_index);
			
			if(settings.slideshow) {
				start_slideshow();
				if(settings.slideshow_pause) {
					jQ.mouseenter(stop_slideshow);
					jQ.mouseleave(start_slideshow);
				}
			}
			
		}();
		// fc_slider OBJ END
	};

	/**
	 * attach TO jQuery
	 */

	$.fn.fc_slider = function(config) {
		var slider = this.data('fc_slider') || {};
		jQ = this;
		if (slider[config]) {
			return slider[config].apply(this, Array.prototype.slice.call(
					arguments, 1));
		} else if (typeof config === 'object' || !config) {
			var slider = new fc_slider(jQ, config);
			jQ.data('fc_slider', slider);
		} else {
			console.log('Error',
					'Object fc_slider has no method .' + config + '()');
		}
	};
})(jQuery);