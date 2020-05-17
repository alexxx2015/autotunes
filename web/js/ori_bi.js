$(document).ready(function(){
	var v_bi = new bi();
	v_bi.init();
});

function bi(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		
		$('select[name="bikeBrand"]').change(function(p_ev){
				if(!$.isArray($(this).data('bid'))){
					$(this).data('bid',new Array());
				}
				
				var $_bid = $(this).val();
				var $_bb = this;
				var $_data = $(this).data('bid');
				if($_data[$_bid] != null){
					$_this.setBM($_data[$_bid]);
				}
				else{
					$.getJSON('/bike/agbm', 
							{'bid':$_bid},
							function(p_dat){
								if(p_dat.r == true){
									$_data[$_bid] = p_dat;
									$($_bb).data('bid', $_data);
								}	
								$_this.setBM(p_dat);
							});
				}
				
		});

		$('textarea[name="bikeDesc"]').keyup(function(p_ev){
			var $_maxLength = 1000;
			var $_val = $(this).val();
			if($_val.length > $_maxLength){
				$_val = $_val.substr(0, $_maxLength);
				$(this).val($_val);
			}
			$('#bikeDescChars').html($_val.length);
		});
		
		$('textarea[name="userAdress"]').keyup(function(p_ev){
			var $_maxLength = 100;
			var $_val = $(this).val();
			if($_val.length > $_maxLength){
				$_val = $_val.substr(0, $_maxLength);
				$(this).val($_val);
			}
			$('#userAdressChars').html($_val.length);
		});
		
		var photoUploadBtn = $('.photoUpload');
		if(photoUploadBtn.length > 0){
			new AjaxUpload(	photoUploadBtn, 
							{	
								action: '/bike/aful', 
								name:'userPhoto',
								data: {'bid':g_arr["BIKE_ID"]},
								autoSubmit: true,
								onSubmit: function(file, extension){
									extension = extension.toLowerCase();
									if ((extension != 'jpg')
										&& (extension != 'jpeg')
										&& (extension != 'png')){
										return false;
									}
								},
								onComplete: function(file, response){
									$_this.handlePhotoUpload(file, jQuery.parseJSON(response));
								}	
							});
		}
		$_this.init_upload_gal();
		
		$('#bikeInsertForm input[name="bikeDel"]').click(function(){
			$('#bikeInsertForm').data('submit', 'bikeDel');
		});
		
		$('#bikeInsertForm input[name="bikeSafe"]').click(function(){
			$('#bikeInsertForm').data('submit', 'bikeSafe');
		});
		
		$('#bikeInsertForm').submit(function(p_ev){
			var _return = true;
			var _submit = $('#bikeInsertForm').data('submit');
			if ((_submit != null) && (_submit == 'bikeDel')){
				_return = confirm(g_arr["INFO_4"]);				
			}			
			else if ((_submit != null) && (_submit == 'bikeSafe')){
				_return = confirm(g_arr["INFO_5"]);				
			}			
			return _return;
		});
		
	};
	
	this.setBM = function(p_dat){
		//if((p_dat != null) && (p_dat.r == true)){
			
			var $_cont = '<option value="-1">'+g_arr["TXT40"]+'</option>';
			var $_bm;
			if(p_dat.bm != null){			
				for(i = 0; i < p_dat.bm.length; i++){
					$_bm = p_dat.bm[i];
					$_cont += '<option value="'+$_bm.bmid+'">'+$_bm.bmn+'</option>';
				}
			}
			$('select[name="bikeModel"]').html($_cont);
		//}
	};
	
	this.handlePhotoUpload = function(p_file, p_resp){
		if(p_resp.r == true){
			var $_img = g_arr["GAL_IMG_PAT"];
			$_img = $_img.replace(/p1/, p_resp.tu);
			$_img = $_img.replace(/p2/, p_resp.h);
			$('#photoGallery').append($_img);
			$_this.init_upload_gal();
		}
	};
	
	this.init_upload_gal = function(){
		$('#photoGallery .delImg').click(function(p_ev){
			var $_thisParent = $(this).parent(); 
			var $_imgID = $($_thisParent).find('img').attr('id');
			$.getJSON('/bike/agfe', 
					{'pid':$_imgID},
					function(p_dat){
						if(p_dat.r == true){
							$($_thisParent).detach();
						}	
					});
		});
	};
}