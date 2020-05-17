$(document).ready(function(){
	var v_qs = new qs();
	v_qs.init();
});

function qs(){
	var _this;
	
	this.init = function(){
		$_qs = $('#qs');
		$_qs_form_html = $('#qs form').html();
		$($_qs).find('form').remove();
		$($_qs).find('.box').append($_qs_form_html);
	};
}