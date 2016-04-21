;(function($, window, document, undefined) {
	var SwipeSlide = function(container, options){
		this.options = $.extend({
			first: 0,
			visibleSlides: 1,
			vertical: false,
			tolerance:0.3,
			delay: 0.3,
			easing: 'ease-out',
			autoPlay: false,
			loop: false,
			useTranslate3d: true,
			bulletNavigation: 'link',
			directionalNavigation: false,
			afterChange: null
		}, options)
		this.isVertical  = !!this.options.vertical;
		this.container   = $(container).addClass('swiper').addClass('ui-swipeslide-'+(this.isVertical ? 'vertical' : 'horizontal'));
		this.reel        = this.container.children().first().addClass('swipe_ulist');
		this.slides      = this.reel.children('li').addClass('swipe_item');
		
		this.numPages    = Math.ceil(this.slides.length / this.options.visibleSlides);
		this.currentPage = this.validPage(this.options.first);
		this.touch       = {};
		//this.isTouch     = 'ontouchstart' in $('html');
		this.isTouch     = true;
		this.events      = {
			start: this.isTouch ? 'touchstart' : 'mousedown',
			move:  this.isTouch ? 'touchmove'  : 'mousemove',
			end:   this.isTouch ? 'touchend' : 'mouseup',
			click: this.isTouch ? 'tap' : 'click'
		};
		this.setup();
		this.addEventListeners();
		if(this.options.directionalNavigation){
			this.setupDirectionalNavigation()
		}
		if(this.options.bulletNavigation){
			this.setupBulletNavigation()
		}
		if(this.options.autoPlay){
			this.autoPlay()
		}
	}

	SwipeSlide.prototype = {
		page: function(index){
			this.stopAutoPlay();
			var newPage = this.validPage(index), 
				callback;
			if(this.currentPage != newPage){
				if($.isFunction(this.options.beforeChange)){
					this.options.beforeChange(this, this.currentPage, newPage);
				}
				this.currentPage = newPage;
				callback = $.proxy(this.callback, this);

			}
			else if(this.options.autoPlay){
				callback = $.proxy(this.autoPlay, this)
			}
			this.move(0, this.options.delay, callback);
			if (this.options.bulletNavigation) {
				this.setActiveBullet()
			}
		},

		first: function(){ this.page(0) },

		next: function(){ this.page(this.currentPage+1) },

		prev: function(){ this.page(this.currentPage-1) },

		last: function(){ this.page(this.numPages-1) },

		isFirst: function(){ return this.currentPage == 0 },

		isLast: function(){ return this.currentPage == this.numPages-1 },

		validPage: function(num){
			return Math.max(Math.min(num, this.numPages-1), 0) 
		},

		autoPlay: function(){
			if (this.timeout){
				return false;
			}
			var fn = this.isLast() ? this.first : this.next
			this.timeout = setTimeout($.proxy(fn, this), this.options.autoPlay * 3000) 
		},

		stopAutoPlay:  function(){ 
			clearTimeout(this.timeout);
			delete this.timeout ;
		},

		visibleSlides: function(){
			return this.slides.slice(this.currentPage, this.currentPage+this.options.visibleSlides)
		},

		move: function(distance, delay, callback) {
			this.reel.animate(this.animationProperties(distance), { duration: delay * 1000, easing: this.options.easing, complete: callback })
		},

		animationProperties: function(distance) {
			var position,
				props = {};
			
			position = -this.currentPage * this.dimension + distance;
			
			var swiper = Math.ceil(this.slides.width()) * this.slides.length;
			
			if(swiper - Math.abs(position) <= this.dimension){
				position = -(swiper - this.dimension) + distance
			}
			
			if (this.options.useTranslate3d) {
			  props['translate3d'] = (this.isVertical ? '0,'+position : position+'px,0') + ',0'
			} else {
			  props[this.isVertical ? 'translateY' : 'translateX'] = position
			}
			return props
		},

		setup: function(){
			var fn = this.isVertical ? 'height' : 'width'
			this.dimension = this.container[fn]()
			this.tolerance = this.options.tolerance * this.dimension / 20
			this.slides[fn](Math.round(this.dimension / this.options.visibleSlides))
			this.reel[fn](Math.ceil(this.slides.width()) * this.slides.length)
			console.log(this.slides.width())
			this.move(0,0)
		},

		addEventListeners: function(){
			this.reel
				.on(this.events.start, $.proxy(this.touchStart, this))
				.on(this.events.move,  $.proxy(this.touchMove, this))
				.on(this.events.end,   $.proxy(this.touchEnd, this))
			
			this.container
				.on(this.events.click, '.next',  $.proxy(this.next,  this))
				.on(this.events.click, '.prev',  $.proxy(this.prev,  this))
				.on(this.events.click, '.first', $.proxy(this.first, this))
				.on(this.events.click, '.last',  $.proxy(this.last,  this))

			$(window).on('resize', $.proxy(this.setup, this))
		},

		touchStart: function(e){
			this.touch.start = this.trackTouch(e)
			delete this.isScroll
			if (!this.isTouch) return false
		},

		touchMove: function(e){
			if (!this.touch.start) return
			this.touch.end = this.trackTouch(e)
			var distance   = this.distance(this.isVertical)
			if (typeof this.isScroll == 'undefined') {
				this.isScroll = Math.abs(distance) < Math.abs(this.distance(!this.isVertical))
			}
			if (!this.isScroll) {
				this.stopAutoPlay()
				this.move(this.withResistance(distance), 0)
				return false
			}
		},

		touchEnd: function(e){
			if (!this.isScroll) {
				var distance = this.distance(this.isVertical), add = 0
				if (Math.abs(distance) > this.tolerance)
				add = distance < 0 ? 1 : -1
				this.page(this.currentPage + add)
			}
			this.touch = {}
			//return false
		},

		trackTouch: function(e) {
			var o = this.isTouch ? e.touches[0] : e
			return { x: o.pageX, y: o.pageY }
		},

		distance: function(vertical) {
			var d = vertical ? 'y' : 'x'
			try { return this.touch.end[d] - this.touch.start[d] } catch(e) {return 0}
		},

		withResistance: function(d){
			if (this.isFirst() && d > 0 || this.isLast() && d < 0) {
				d /= (1 + Math.abs(d) / this.dimension)
			}
			return d
		},

		callback: function(){
			if ($.isFunction(this.options.afterChange)) {
				this.options.afterChange(this, this.currentPage)
			}
			if (this.options.autoPlay) {
				this.autoPlay()
			}
		},

		setupDirectionalNavigation: function() {
			this.container.append('<ul class="ui-swipeslide-nav"><li class="prev">Previous</li><li class="next">Next</li></ul>')
		},

		setupBulletNavigation: function() {
			this.navBullets = $('<div class="pagination"></div>')
			for (i=0; i<this.numPages; i++) {
				this.navBullets.append('<span data-index="'+i+'"></span>')
			}
			if (this.options.bulletNavigation == 'link') {
			  this.navBullets.on(this.events.click, 'span', $.proxy(function(e){
				this.page(parseInt($(e.currentTarget).data('index'), 10))
			  }, this))
			}
			this.container.append(this.navBullets)
			this.setActiveBullet()
		},

		setActiveBullet: function() {
			this.navBullets.children('span').removeClass('active').eq(this.currentPage).addClass('active')
		}

	}

	$.fn.swipeSlide = function(options) {
		var swipe = SwipeSlide
			return this.each(function() {
			  var s = new swipe(this, options);
			  if (typeof($(this).data("swipeInstance", s).data("swipeInstance")) === "string") { $(this).removeAttr("data-swipeInstance"); }
			  return;
		});
	}
	
})(window.Zepto)