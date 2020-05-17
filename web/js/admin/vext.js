$(document).ready(function(){
	var _vext = new vext();
	_vext.init();
});

function vext(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		$('input[name~="carExtActive"]').click(function(){
			if($(this).is(':checked')){
				$('select[name~="carExtParent"]').removeAttr('disabled');
			}else{
				$('select[name~="carExtParent"]').attr('disabled','true');
			}
		});
		$('input[name~="bikeExtActive"]').click(function(){
			if($(this).is(':checked')){
				$('select[name~="bikeExtParent"]').removeAttr('disabled');
			}else{
				$('select[name~="bikeExtParent"]').attr('disabled','true');
			}
		});
		$('input[name~="truckExtActive"]').click(function(){
			if($(this).is(':checked')){
				$('select[name~="truckExtParent"]').removeAttr('disabled');
			}else{
				$('select[name~="truckExtParent"]').attr('disabled','true');
			}
		});
	};
}