var EpiloumSlider = function(parentSelector, listWidth){
	// Set Properties
	this.parent = $(parentSelector);
	this.lists = this.parent.children('li');
	this.length = this.lists.length;
	this.width = listWidth? listWidth: this.lists.width();
	this.idx = 0;
	this.period = 300;			// Unit: Microsecond
	this.easing = 'swing';		// Animation Type

	// Initialize
	this._init();
}

EpiloumSlider.prototype = {

	_init: function(){
		var inst = this;
		var idx = this.idx;
		var len = this.length;
		var dist = this.width;


		// Setting CSS for Parent
		this.parent.css('overflow', 'hidden');
		this.parent.css('position', 'relative');

		// Setting CSS for List
		this.lists.css('position', 'absolute');
		this.lists.css('top', '0px');
		this.lists.each(function(i){
			$(this).css('left', dist * i);
		});
	},

	_ani: function(){
		var inst = this;
		var idx = this.idx;
		var len = this.length;
		var dist = this.width;

		this.lists.each(function(i){
			$(this).animate({
				left: left = inst.width * (i - idx)
			}, {
				duration: inst.period,
				easing: inst.easing
			});			
		});
	},

	left: function(){
		this.idx = (this.idx - 1 + this.length) % this.length;
		this._ani();
	},

	right: function(){
		this.idx = (this.idx + 1) % this.length;
		this._ani();
	},

	pos: function(i){
		this.idx = Math.min(Math.max(0, i), this.length);
		this._ani();
	},

	setEasing: function(v){
		this.easing = v;
	},

	setPeriod: function(v){
		this.period = parseInt(v, 10);
	}
}