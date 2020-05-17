$(document).ready(function(){
	var v_gallery = new gallery();
	v_gallery.init();
});

function gallery(){
	var _this;
	
	this.init = function(){
		_this = this;
		
		if(typeof $gl_view == 'undefined'){
			$gl_view = 3;
		}
		
		//Carousel options
		$('#photoGallery').carouFredSel({
			circular: false,
			auto: false,
			prev: "#prev",
			next: "#next",
			items: {
				visible: $gl_view
				}
		});
		
		//Photo options
		$('#photoGallery div a').prettyPhoto({
			slideshow: false,
			theme: 'dark_square'
			
		});
	};
}