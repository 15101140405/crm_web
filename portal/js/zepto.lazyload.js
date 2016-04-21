;(function($){
	$.extend($.fn, {
		lazyload: function(){
			var imgs = this,
				loadIndex = 0,
				prevTime = new Date().getTime();
			
			function getHeight() {
				return window.innerHeight || screen.availHeight;
			};

			function inViewport(el) {
				var height = getHeight(),
					min = window.pageYOffset - height / 2,
					max = getMax(),
					elTop = el.offset().top;
				min = min < 0 ? 0 : min;
				return elTop >= 0  && elTop >= min && elTop <= max;
			};
			
			function getMax() {
				return window.pageYOffset + getHeight() * 1.2;
			};
		
			function load(_self) {
				var img = new Image(),
				url = _self.data('lazyload');
				img.onload = function() {
					_self.attr('src', url).removeAttr('data-lazyload');
				}
				url && (img.src = url);
			};

			$(window).on('scroll.lazyload',function(e){
				var curTime = new Date().getTime(),
					oldIndex = loadIndex,
					lazys = imgs,
					height = 120,
					rad = parseInt(Math.random() * 10) % 2 == 0,
					length = lazys.length;
				if (curTime - prevTime < 100) {
					return;
				}
				loadIndex++;
				prevTime = curTime;
				for (var i = 0; i < length; i++) {
					if (oldIndex + 1 != loadIndex) {
						break;
					}
					var node = lazys[i],
						Qme = $(imgs[i]);
					if (Qme.offset().top > getMax()) {
						break;
					}
					if (inViewport(Qme)) {
						load(Qme);
					}
				}
			});
			
			setTimeout(function(){
				$(window).trigger('scroll');
			},100);

    	}
	});
	
	$(function () {
        $('[data-lazyload]').lazyload();
    });
	
})(Zepto)