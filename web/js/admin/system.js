$(document).ready(function(){
	var v_system = new system();
	v_system.init();
});

function system(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		$('#systemForm').submit(function(p_ev){
			var _return = confirm(g_arr["INFO_5"]);
			return _return;
		});
	};
}