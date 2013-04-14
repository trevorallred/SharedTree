/**
 * AutoComplete
 * @copyright © 2006, Beau D. Scott
 * @author Beau D. Scott
 * @version 1.1
 */

var AutoComplete = Class.create();
AutoComplete.prototype = {

	/**
	 * @param {Object} bindField ID of form element, or dom element,  to suggest on
	 * @param {String} URL of dictionary
	 * @param {Object} options
	 */
	initialize: function(bindField, action, options)
	{
		this.options = Object.extend({
			/**
			 * @param {Number} Number of options to display before scrolling
			 */
			size: 10,
			/**
			 * @param {String} CSS class name for autocomplete selector
			 */
			cssClass: null,
			/**
			 * @param {Object} JavaScript callback function to execute upon selection
			 */
			onSelect: null,
			/**
			 * @param {Number} minimum characters needed before an suggestion is executed
			 */
			threshold: 3,
			/**
			 * @param {Number} Time delay between key stroke and execution
			 */
			delay: .2
		}, options);

		this.action = action;
		this.bindField = bindField;

		this._elements = {
			selector: document.createElement('select'),
			input: $(this.bindField)
		};

		if(!this._elements.input)
			alert('Failed to bind to form field[' + this.options.bindField + ']');

		if(!this.action)
			alert('No action url specified');

		this._timeout = null;
		this._visible = false;

		this.initialized = false;
		Event.observe(window, 'load', this.draw.bind(this));
		Event.observe(window, 'resize', this._reposition.bind(this));
		Event.observe(window, 'scroll', this._reposition.bind(this));
	},

	/**
	 * Initializes the UI components of the object
	 * @param {Object} e Event
	 */
	draw: function(e)
	{
		if(this.initialized) return;
		if(this.options.cssClass)
			this._elements.selector.className = this.options.cssClass;
		Element.setStyle(this._elements.selector, {
			display: 'none',
			position: 'absolute',
			width: this._elements.input.offsetWidth + 'px'
		});
		this._elements.selector.size = this.options.size;
		document.body.appendChild(this._elements.selector);
		this._elements.selector.autocomplete = 'off';
		Event.observe(this._elements.input, 'keyup', this.suggest.bindAsEventListener(this));
		Event.observe(this._elements.input, 'keydown', this.suggest.bindAsEventListener(this));
		Event.observe(this._elements.input, 'blur', this.hide.bindAsEventListener(this));
		Event.observe(this._elements.selector, 'focus', this.show.bindAsEventListener(this));
		Event.observe(this._elements.selector, 'click', this.select.bindAsEventListener(this));
		this.initialized = true;
	},

	hide: function(e)
	{
		var trigger = null;
		if(e)
			trigger = Event.element(e);
		this._visible = false;
		if(window.Scriptaculous && trigger != this._elements.selector)
		{
			new Effect.BlindUp(this._elements.selector, {
				duration: this.options.delay,
				queue: 'end'
			});
		}
		else
		{
			Element.setStyle(this._elements.selector,{
				display: 'none'
			});
		}
	},

	show: function(e)
	{
		var trigger = null;
		if(e)
			trigger = Event.element(e);
		if(this._elements.selector.options.length)
		{
			if(window.Scriptaculous && trigger != this._elements.selector)
			{
				new Effect.BlindDown(this._elements.selector,{
					duration: this.options.delay,
					queue: 'end'
				});
			}
			else
			{
				Element.setStyle(this._elements.selector,{
					display: 'inline'
				});
			}
			this._reposition();
			this._visible = true;
		}
	},

	/**
	 * Removes the timeout function set by suggest
	 */
	_cancelTimeout: function()
	{
		if(!this._timeout) return;
		clearTimeout(this._timeout);
		this._timeout = null;
	},

	/**
	 * Triggers the suggest interaction
	 * @param {Object} e Event
	 */
	suggest: function(e)
	{
		this._cancelTimeout();
		var key = Event.keyPressed(e);

		var ignoreKeys = [
			20, // caps lock
			16, // shift
			17, // ctrl
			91, // Windows key
			121, // F1 - F12
			122,
			123,
			124,
			125,
			126,
			127,
			128,
			129,
			130,
			131,
			132,
			45, // Insert
			36, // Home
			35, // End
			33, // Page Up
			34, // Page Down
			144, // Num Lock
			145, // Scroll Lock
			44, // Print Screen
			19, // Pause
			93, // Mouse menu key
		];
		if(ignoreKeys.indexOf(key) > -1)
			return true;

		if(e.type == 'keydown')
		{
			if(Event.KEY_ESC == key)
			{
				this.cancel();
				Event.stop(e);
				return false;
			}
			if(Event.KEY_TAB == key)
			{
				this.cancel();
				return true;
			}
			return true;
		}

		switch(key)
		{
			case Event.KEY_LEFT:
			case Event.KEY_RIGHT:
				return true;
				break;
			case Event.KEY_TAB:
			case Event.KEY_BACKSPACE:
			case 46: //Delete
				this.cancel();
				return true;
				break;
			case Event.KEY_RETURN:
				this.select(e);
				return true;
				break;
			case Event.KEY_ESC:
				this.cancel();
				return false;
				break;
			case Event.KEY_UP:
			case Event.KEY_DOWN:
				this.interact(e);
				Event.stop(e);
				return false;
				break;
			default:
				break;
		}
		if(this._elements.input.value.length < this.options.threshold) return true;
		else
			this._timeout = setTimeout(this._sendRequest.bind(this), 1000 * this.options.delay);

	},

	_sendRequest: function()
	{
		this._request = new Ajax.Request(this.action + this._elements.input.value, {
			onComplete: this._process.bind(this)
		});
	},

	/**
	 * Adjusts the positioning of the suggestion box between displays and on screen resizing
	 * @param {Object} e Event
	 */
	_reposition: function(e)
	{
		if(!this.initialized) return;
		var pos = Position.cumulativeOffset(this._elements.input);
		pos.push(pos[0] + this._elements.input.offsetWidth);
		pos.push(pos[1] + this._elements.input.offsetHeight);
		Element.setStyle(this._elements.selector,{
			left: pos[0] + 'px',
			top: pos[3] + 'px'
		});
	},

	/**
	 * Processes the resulting XML from a suggestion request, adds options to the suggestion box.
	 * @param {Object} objXML XML
	 */
	_process: function(objXML)
	{
		this._elements.selector.options.length = 0;
		var xml = objXML.responseXML;
		if(!xml)
		{
			//alert(objXML.responseText);
			return false;
		}
		var suggestions = xml.getElementsByTagName('suggestion');
		for(i = 0; i < suggestions.length; i++)
		{
			suggestion = suggestions.item(i).firstChild.nodeValue;
			var opt = new Option(suggestion, suggestion);
			document.all ? this._elements.selector.add(opt) : this._elements.selector.add(opt, null);
		}
		if(this._elements.selector.options.length > (this.options.size))
			this._elements.selector.size = this.options.size;
		else
			this._elements.selector.size = this._elements.selector.options.length > 1 ? this._elements.selector.options.length : 2;
		if(this._elements.selector.options.length)
		{
			this._elements.selector.selectedIndex = 0;
			this.show();
		}
		else
			this.cancel();
	},

	/**
	 * Clears and hides the suggestion box.
	 * @param {Object} e Event
	 */
	cancel: function(e)
	{
		this.hide(e);
		this._elements.selector.options.length = 0;
	},

	/**
	 * Captures the currently selected suggestion option to the input field
	 * @param {Object} e Event
	 */
	select: function(e)
	{
		if(this._elements.selector.options.length)
			this._elements.input.value = this._elements.selector.options[this._elements.selector.selectedIndex].value;
		this.cancel();
		if(typeof this.options.onSelect == 'function')
			this.options['onSelect'](this._elements.input);
	},

	/**
	 * Processes key interactions with the input field, including navigating the selected option
	 * with the up/down arrows, esc cancelling and selecting the option.
	 * @param {Object} e
	 */
	interact: function(e)
	{
		if(!this._visible) return;

		var key = Event.keyPressed(e);
		if(key != Event.KEY_UP && key != Event.KEY_DOWN) return;
		var mx = this._elements.selector.options.length;

		if(key == Event.KEY_UP)
		{
			if(this._elements.selector.selectedIndex == 0)
				this._elements.selector.selectedIndex = this._elements.selector.options.length - 1;
			else
				this._elements.selector.selectedIndex--;
		}
		else
		{
			if(this._elements.selector.selectedIndex == this._elements.selector.options.length - 1)
				this._elements.selector.selectedIndex = 0;
			else
				this._elements.selector.selectedIndex++;
		}
	}

};

/**
 * Various Prototype Event extensions
 */
Object.extend(Event, {
	KEY_BACKSPACE: 8,
	KEY_TAB:       9,
	KEY_RETURN:   13,
	KEY_ESC:      27,
	KEY_LEFT:     37,
	KEY_UP:       38,
	KEY_RIGHT:    39,
	KEY_DOWN:     40,
	KEY_DELETE:   46,
	KEY_SHIFT:    16,
	KEY_CONTROL:  17,
	KEY_CAPSLOCK: 20,
	KEY_SPACE:	  32,
	keyPressed: function(event)
	{
		return document.all ? window.event.keyCode : event.which;
	}
});

