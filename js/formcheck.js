

var FormCheck = new Class({
	options : {

		tips_class: 'tipsbox',			//tips error class
		error_class: 'error_f',			//div error class

		display : {
			showErrors : 1,				//0 : onSubmit, 1 : onSubmit & onBlur
			errorsLocation : 1,			//1 : tips, 2 : before, 3 : after
			indicateErrors : 1,			//0 :  none, 1 : one, 2 : all
			tipsOffsetX : -32,			//Left position of the tips box (margin-left)
			tipsOffsetY : -12,			//Top position of the tips box (margin-bottom)
			tipsPosition : 'relative',	//If you want to set the tips position with relative or absolute value (page not centered)
			tipsContainer : 'undef',	//Container of fields, to get right positions.
			listErrorsAtTop : false,	//list all errors at the top of the form
			scrollToFirst : true,		//Smooth scroll the page to first error
			fadeDuration : 1000			//Transition duration
		},

		alerts : {
			required     : "This field is required.",
			alpha        : "This field accepts alphabetic characters only.",
			alphanum     : "This field accepts alphanumeric characters only.",
			nodigit      : "No digits are accepted.",
			digit        : "Please enter a valid integer.",
			digitmin     : "The number must be at least %0",
			digitltd     : "The value must be between %0 and %1",
			number       : "Please enter a valid number.",
			email        : "Please enter a valid email: <br /><span>E.g. yourname@domain.com</span>",
			spamcheck    : "<span>2 + 3 = ???</span>",
			phone        : "Please enter a valid phone.",
			url          : "Please enter a valid url: <br /><span>E.g. http://www.domain.com</span>",
			confirm      : "This field is different from %0",
			differs      : "This value must be different of %0",
			length_str   : "Text is too short, it must be between %0 and %1",
			lengthmax    : "Text is too short, it must be at max %0",
			lengthmin    : "Text is too short, it must be at least %0",
			checkbox     : "Please check the box",
			radios       : "Please select a radio",
			select       : "Please choose a value"
		},

		regexp : {
			required     : /[^.*]/,
			alpha        : /^[a-z ._-]+$/i,
			alphanum     : /^[a-z0-9 ._-]+$/i,
			digit        : /^[-+]?[0-9]+$/,
			nodigit      : /^[^0-9]+$/,
			spamcheck    : /^[5]+$/,
			number       : /^[-+]?\d*\.?\d+$/,
			email        : /^[a-z0-9._%-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i,
			phone        : /^[\d\s ().-]+$/,
			url          : /^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i
		}
	},



	/*
	Constructor: initialize
		Constructor

		Add event on formular and perform some stuff, you now, like settings, ...
	*/
	initialize : function(form, options) {
		if (this.form = $(form)) {
			this.form.isValid = true;
			this.regex = ['length'];
			this.setOptions(options);

			//internalization
			if (typeof(formcheckLanguage) != 'undefined') this.options.alerts = formcheckLanguage;

			this.validations = [];
			this.alreadyIndicated = false;
			this.firstError = false;

			var regex = new Hash(this.options.regexp);
			regex.each(function(el, key) {
				this.regex.push(key);
			}, this)

			this.form.getElements("*[class*=validate]").each(function(el) {
				el.validation = [];
				var classes = el.getProperty("class").split(' ');
				classes.each(function(classX) {
					if(classX.match(/^validate(\[.+\])$/)) {
						var validators = eval(classX.match(/^validate(\[.+\])$/)[1]);
						for(var i = 0; i < validators.length; i++) {
							el.validation.push(validators[i]);
						}
						this._register(el);
					}
				}, this);
			}, this);

			this.form.addEvents({
				"submit": this._onSubmit.bind(this)
			});
		}
	},

	/*
	Function: _register
		Private method

		Add listener on fields
	*/
	_register : function(el) {
		this.validations.push(el);
		el.errors = [];
		if (this._isChildType(el) == false && this.options.display.showErrors == 1) el.addEvent('blur', function() {
			this._manageError(el, 'blur');
		}.bind(this));
	},

	/*
	Function: _validate
		Private method

		Dispatch check to other methods
	*/
	_validate : function(el) {
		el.errors = [];
		el.isOk = true;
		//On valide l'élément qui n'est pas un radio ni checkbox
		el.validation.each(function(rule) {
			if(this._isChildType(el)) {
				if (this._validateGroup(el) == false) {
					el.isOk = false;
				}
			} else {
				var ruleArgs = [];
				if(rule.match(/^.+\[/)) {
					var ruleMethod = rule.split('[')[0];
					var ruleArgs = eval(rule.match(/^.+(\[.+\])$/)[1].replace(/([A-Z]+)/i, "'$1'"));
				} else var ruleMethod = rule;

				if (this.regex.contains(ruleMethod)) {
					if (this._validateRegex(el, ruleMethod, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (ruleMethod == 'confirm') {
					if (this._validateConfirm(el, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (ruleMethod == 'differs') {
					if (this._validateDiffers(el, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (el.getTag() == "select" || el.type == "checkbox") {
					if (this._simpleValidate(el) == false) {
						el.isOk = false;
					}
				}
			}
		}, this);

		if (el.isOk) return true;
		else return false;
	},

	/*
	Function: _simpleValidate
		Private method

		Perform simple check for select fields and checkboxes
	*/
	_simpleValidate : function(el) {
		if (el.getTag() == 'select' && (el.value == el.options[0].value)) {
			el.errors.push(this.options.alerts.select);
			return false;
		} else if (el.type == "checkbox" && el.checked == false) {
			el.errors.push(this.options.alerts.checkbox);
			return false;
		}
		return true;
	},

	/*
	Function: _validateRegex
		Private method

		Perform regex validations
	*/
	_validateRegex : function(el, ruleMethod, ruleArgs) {
		var msg = "";
		if (ruleArgs[1] && ruleMethod == 'length') {
			if (ruleArgs[1] == -1) {
				this.options.regexp.length = new RegExp("^.{"+ ruleArgs[0] +",}$");
				msg = this.options.alerts.lengthmin.replace("%0",ruleArgs[0]);
			} else {
				this.options.regexp.length = new RegExp("^.{"+ ruleArgs[0] +","+ ruleArgs[1] +"}$");
				msg = this.options.alerts.length_str.replace("%0",ruleArgs[0]).replace("%1",ruleArgs[1]);
			}
		} else if (ruleArgs[0]) {
			this.options.regexp.length = new RegExp("^.{0,"+ ruleArgs[0] +"}$");
			msg = this.options.alerts.lengthmax.replace("%0",ruleArgs[0]);
		} else {
			msg = this.options.alerts[ruleMethod];
		}
		if (ruleArgs[1] && ruleMethod == 'digit') {
			var regres = true;
			if (!this.options.regexp.digit.test(el.value)) {
				el.errors.push(this.options.alerts[ruleMethod]);
				regres = false;
			}
			if (ruleArgs[1] == -1) {
				if (el.value >= ruleArgs[0]) var valueres = true; else var valueres = false;
				msg = this.options.alerts.digitmin.replace("%0",ruleArgs[0]);
			} else {
				if (el.value >= ruleArgs[0] && el.value <= ruleArgs[1]) var valueres = true; else var valueres = false;
				msg = this.options.alerts.digitltd.replace("%0",ruleArgs[0]).replace("%1",ruleArgs[1]);
			}
			if (regres == false || valueres == false) {
				el.errors.push(msg);
				return false;
			}
		} else if (this.options.regexp[ruleMethod].test(el.value) == false)  {
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: _validateConfirm
		Private method

		Perform confirm validations
	*/
	_validateConfirm: function(el,ruleArgs) {
		if (el.validation.contains('required') == false) {
			//el.validation.push('required');
		}
		var confirm = ruleArgs[0];
		if(el.value != this.form[confirm].value){
			msg = this.options.alerts.confirm.replace("%0",ruleArgs[0]);
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: _validateDiffers
		Private method

		Perform differs validations
	*/
	_validateDiffers: function(el,ruleArgs) {
		var confirm = ruleArgs[0];
		if(el.value == this.form[confirm].value){
			msg = this.options.alerts.differs.replace("%0",ruleArgs[0]);
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: _isChildType
		Private method

		Determine if the field is a group of radio or not.
	*/
	_isChildType: function(el) {
		var elType = el.type.toLowerCase();
		if((elType == "radio")) return true;
		return false;
	},

	/*
	Function: _validateGroup
		Private method

		Perform radios validations
	*/
	_validateGroup : function(el) {
		el.errors = [];
		var nlButtonGroup = this.form[el.getProperty("name")];
		el.group = nlButtonGroup;
		var cbCheckeds = false;

		for(var i = 0; i < nlButtonGroup.length; i++) {
			if(nlButtonGroup[i].checked) {
				cbCheckeds = true;
			}
		}
		if(cbCheckeds == false) {
			el.errors.push(this.options.alerts.radios);
			return false;
		} else {
			return true;
		}
	},

	/*
	Function: _listErrorsAtTop
		Private method

		Display errors
	*/
	_listErrorsAtTop : function(obj) {
		if(!this.form.element) {
			 this.form.element = new Element('div', {'id' : 'errorlist', 'class' : this.options.error_class}).injectTop(this.form);
		}
		if ($type(obj) == 'collection') {
			new Element('p').setHTML("<span>" + obj[0].name + " : </span>" + obj[0].errors[0]).injectInside(this.form.element);
		} else {
			if ((obj.validation.contains('required') && obj.errors.length > 0) || (obj.errors.length > 0 && obj.value && obj.validation.contains('required') == false)) {
				obj.errors.each(function(error) {
					new Element('p').setHTML("<span>" + obj.name + " : </span>" + error).injectInside(this.form.element);
				}, this);
			}
		}
	},

	/*
	Function: _manageError
		Private method

		Manage display of errors boxes
	*/
	_manageError : function(el, method) {
		var isValid = this._validate(el);
		if (((!isValid && el.validation.contains('required')) || (!el.validation.contains('required') && el.value && !isValid))) {
			if(this.options.display.listErrorsAtTop == true && method == 'submit')
				this._listErrorsAtTop(el, method);
			if (this.options.display.indicateErrors == 2 ||this.alreadyIndicated == false || el.name == this.alreadyIndicated.name)
				{
					this._addError(el);
					return false;
				}
		} else if ((isValid || (!el.validation.contains('required') && !el.value)) && el.element) {
			this._removeError(el);
			return true;
		}
		return true;
	},

	/*
	Function: _addError
		Private method

		Add error message
	*/
	_addError : function(obj) {
		this.alreadyIndicated = obj;
		if(!this.firstError) this.firstError = obj;
		if(!obj.element) {
			if (this.options.display.errorsLocation == 1) {
				if (this.options.display.tipsPosition == 'relative') {
					var marginLeft = this.options.display.tipsOffsetX;
					if (this.options.display.tipsContainer = 'undef')
						var displacement = this.form.getCoordinates().left;
					else
						var displacement = $(this.options.display.tipsContainer).getCoordinates().left;
					var options = {
						'opacity' : 0,
						'position' : 'absolute',
						'margin-left' : obj.getCoordinates().right - displacement + this.options.display.tipsOffsetX
					}
				} else if (this.options.display.tipsPosition == 'absolute') {
					var options = {
						'opacity' : 0,
						'position' : 'absolute',
						'margin-left' : this.options.display.tipsOffsetX,
						'left' : obj.getCoordinates().right,
						'bottom' : obj.getCoordinates().top
					}
				}
					obj.element = new Element('div', {'id' : 'diverror' + obj.name, 'class' : this.options.tips_class, 'styles' : options});
					obj.element.injectInside(this.form);

			} else if (this.options.display.errorsLocation == 2){
				obj.element = new Element('div', {'id' : 'diverror' + obj.name, 'class' : this.options.error_class, 'styles' : {'opacity' : 0}});
				obj.element.injectBefore(obj);
			} else if (this.options.display.errorsLocation == 3){
				obj.element = new Element('div', {'id' : 'diverror' + obj.name, 'class' : this.options.error_class, 'styles' : {'opacity' : 0}});

				if ($type(obj.group) == 'object' || $type(obj.group) == 'collection')
					obj.element.injectAfter(obj.group[obj.group.length-1]);
				else
					obj.element.injectAfter(obj);
			}
		}
		if (obj.element) {
			obj.element.empty();
			if (this.options.display.errorsLocation == 1) {
				var errors = [];
				obj.errors.each(function(error) {
					errors.push(new Element('p').setHTML(error));
				});
				var tips = this._makeTips(errors).injectInside(obj.element);
				obj.element.setStyle('top', obj.getCoordinates().top - tips.getCoordinates().height - this.options.display.tipsOffsetY);
			} else {
				obj.errors.each(function(error) {
					new Element('p').setHTML(error).injectInside(obj.element);
				});
			}

			if (!window.ie7 && obj.element.getStyle('opacity') == 0)
				new Fx.Styles(obj.element, {'duration' : this.options.display.fadeDuration}).start({'opacity':[1]});
			else
				obj.element.setStyle('opacity', 1);
		}
	},

	/*
	Function: _removeError
		Private method

		Remove the error display
	*/
	_removeError : function(obj) {
		this.firstError = false;
		this.alreadyIndicated = false;
		obj.errors = [];
		obj.isOK = true;
		if (this.options.display.errorsLocation == 2)
			new Fx.Styles(obj.element, {'duration' : this.options.display.fadeDuration}).start({ 'height':[0] });
		if (!window.ie7) {
			new Fx.Styles(obj.element, {
				'duration' : this.options.display.fadeDuration,
				'onComplete' : function() {
					if (obj.element) {
						obj.element.remove();
						obj.element = false;
					}
				}.bind(this)
			}).start({ 'opacity':[1,0] });
		} else {
			obj.element.remove();
			obj.element = false;
		}
	},

	/*
	Function: _focusOnError
		Private method

		Create set the focus to the first field with an error if needed
	*/
	_focusOnError : function (obj) {
		if (this.options.display.scrollToFirst && !this.alreadyFocused && this.alreadyIndicated.element && !this.isScrolling) {
			if (this.options.display.errorsLocation == 1) new Fx.Scroll(window, {onComplete : function() {this.isScrolling = false;}.bind(this)}).scrollTo(0,obj.element.getCoordinates().top);
			else if (this.options.display.errorsLocation == 2) new Fx.Scroll(window, {onComplete : function() {this.isScrolling = false;}.bind(this)}).scrollTo(0,obj.getCoordinates().top-30);
			this.isScrolling = true;
			obj.focus();
			this.alreadyFocused = true;
		} else if (this.options.display.scrollToFirst && !this.isScrolling) {
			new Fx.Scroll(window, {onComplete : function() {this.isScrolling = false;}.bind(this)}).scrollTo(0,obj.getCoordinates().top-30);
			this.isScrolling = true;
			obj.focus();
			this.alreadyFocused = true;
		}
	},

	/*
	Function: _makeTips
		Private method

		Create tips boxes
	*/
	_makeTips : function(txt) {
		var table = new Element('table', {'class' : 'tipsbox'});
			table.cellPadding ='0';
			table.cellSpacing ='0';
			table.border ='0';

			var tbody = new Element('tbody').injectInside(table);
				var tr1 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'tipsbox_top_left'}).injectInside(tr1);
					new Element('td', {'class' : 'tipsbox_top'}).injectInside(tr1);
					new Element('td', {'class' : 'tipsbox_top_right'}).injectInside(tr1);
				var tr2 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'tipsbox_left'}).injectInside(tr2);
					var errors = new Element('td', {'class' : 'tipsbox_inner'}).injectInside(tr2);
					var errorImg = new Element('div', {'class' : 'tipsbox_error'}).injectInside(errors);
					txt.each(function(error) {
						error.injectInside(errors);
					});
					new Element('td', {'class' : 'tipsbox_right'}).injectInside(tr2);
				var tr3 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'tipsbox_bottom_left'}).injectInside(tr3);
					new Element('td', {'class' : 'tipsbox_mark'}).injectInside(tr3);
					new Element('td', {'class' : 'tipsbox_bottom_right'}).injectInside(tr3);
		return table;
	},

	/*
	Function: _reinitialize
		Private method

		Reinitialize form before submit check
	*/
	_reinitialize: function() {
		this.validations.each(function(el) {
			if (el.element) {
				el.errors = [];
				el.isOK = true;
				el.element.remove();
				el.element = false;
			}
		});
		if (this.form.element) this.form.element.empty();
		this.alreadyFocused = false;
		this.firstError = false;
		this.alreadyIndicated = false;
		this.form.isValid = true;
	},

	/*
	Function: _onSubmit
		Private method

		Perform check on submit action
	*/
	_onSubmit: function(event) {
		this._reinitialize();

		this.validations.each(function(el) {
			if(!this._manageError(el,'submit')) this.form.isValid = false;
		}, this);
		if(!this.form.isValid) {
			new Event(event).stop();
			if (this.firstError) this._focusOnError(this.firstError);
		}
	}
});
FormCheck.implement(new Options());