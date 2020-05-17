$(document).ready(function(){
	var v_user = new user();
	v_user.init();
});

function user(){
	var $_this;
	
	var $_first;
	
	this.init = function(){
		$_this = this;
		
		$_this.$_first = true;

		$('select[name="userMode"]').change(function(p_ev){
			var $val = $(this).val();
			if($val == 1){
				$('.userInfo label').removeClass('orange')
									.removeClass('bold');
				$('.userInfo label .star').remove();

				$('.userAddInfo label').prepend('<span class="star">* </span>');
				$('.userAddInfo label').addClass('orange')
										.addClass('bold');
			}else{			
				if($_this.$_first != true){
					$('.userAddInfo label .star').remove();
					$('.userAddInfo label').removeClass('orange')
											.removeClass('bold');					
	
					$('.userInfo label').prepend('<span class="star">* </span>');
					$('.userInfo label').addClass('orange')
										.addClass('bold');
				}
			}
			$_this.$_first = false;
		});
		var $val = $('select[name="userMode"]').val();
		if($val == 1){
			$('.userInfo label').removeClass('orange')
								.removeClass('bold');
			$('.userInfo label .star').remove();
			
			$('.userAddInfo label').prepend('<span class="star">* </span>');
			$('.userAddInfo label').addClass('orange')
									.addClass('bold');
		}
	};
}