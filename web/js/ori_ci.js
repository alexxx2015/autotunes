$(document).ready(function(){
	var v_ci = new ci();
	v_ci.init();
});

function ci(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		
		$('select[name="carBrand"]').change(function(p_ev){
				if(!$.isArray($(this).data('cid'))){
					$(this).data('cid',new Array());
				}
				
				var $_cid = $(this).val();
				var $_cb = this;
				var $_data = $(this).data('cid');
				if($_data[$_cid] != null){
					$_this.setCM($_data[$_cid]);
				}
				else{
					$.getJSON('/car/ajagm', 
							{'cid':$_cid},
							function(p_dat){
								if(p_dat.r == true){
									$_data[$_cid] = p_dat;
									$($_cb).data('cid', $_data);
								}	
								$_this.setCM(p_dat);
							});
				}				
		});

		$('select[name="userAds"]').change(function(p_ev){
			var $val = $(this).val();

			if($val == 1){
				$('.userInfo label').removeClass('orange')
									.removeClass('bold');
				$('.userInfo label .star').remove();

				$('.userAddInfo label').prepend('<span class="star">* </span>');
				$('.userAddInfo label').addClass('orange')
										.addClass('bold');
			}else{			
				$('.userAddInfo label .star').remove();
				$('.userAddInfo label').removeClass('orange')
										.removeClass('bold');					

				$('.userInfo label').prepend('<span class="star">* </span>');
				$('.userInfo label').addClass('orange')
									.addClass('bold');
			}
		});
		var $val = $('select[name="userAds"]').val();
		if($val == 1){
			$('.userInfo label').removeClass('orange')
								.removeClass('bold');
			$('.userInfo label .star').remove();
			
			$('.userAddInfo label').prepend('<span class="star">* </span>');
			$('.userAddInfo label').addClass('orange')
									.addClass('bold');
		}
		
		$('textarea[name="carDesc"]').keyup(function(p_ev){
			var $_maxLength = 1000;
			var $_val = $(this).val();
			if($_val.length > $_maxLength){
				$_val = $_val.substr(0, $_maxLength);
				$(this).val($_val);
			}
			$('#carDescChars').html($_val.length);
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
								action: '/car/ajaful', 
								name:'userPhoto',
								data: {'cid':g_arr["CAR_ID"]},
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
		
		//Carousel options
		$('#photoGallery').carouFredSel({
			circular: false,
			auto: false,
			prev: "#prev",
			next: "#next",
			width: "variable",
			items: {
				visible: 3
				}
		});
		
		$_this.init_upload_gal();
		/*
		var uploader = new qq.FileUploader({
			element: '.photoUpload',
			action: '/car/aful',
			allowedExtensions: ['jpg', 'jpeg', 'png'],
			onSubmit: function(id, filename){},
			onComplete: function(id, filename, resp){
				$_this.photoComplete(id, filename, resp);
			}
		});*/
		
	};
	
	this.setCM = function(p_dat){
		//if((p_dat != null) && (p_dat.r == true)){
			var $_cont = '<option value="-1">'+g_arr['TXT40']+'</option>';
			var $_cm;
			if (p_dat.cm != null){			
				for(i = 0; i < p_dat.cm.length; i++){
					$_cm = p_dat.cm[i];
					$_cont += '<option value="'+$_cm.cmid+'">'+$_cm.cmn+'</option>';
				}
			}
			$('select[name="carModel"]').html($_cont);
		//}
	};
	
	this.handlePhotoUpload = function(p_file, p_resp){
		//{"r":true,"h":"1223","tu":"\/pic\/c_13_1223.jpeg"}
		//<div><span  class="delImg">X</span><a rel="prettyPhoto[pp_gal]" href="/pic/c_13_1225.jpeg" ><img src="p1" id="1225"/></a></div>
		if(p_resp.r == true){
			var $_img = g_arr["GAL_IMG_PAT"];
			$_img = $_img.replace(/p1/g, p_resp.tu);
			$_img = $_img.replace(/p2/g, p_resp.h)
			$('#photoGallery').trigger("insertItem", [$_img,"end",true]);
			$_this.init_upload_gal();
		}
	};
	
	this.init_upload_gal = function(){		
		$_delImg = $('#photoGallery .delImg');
		for(i = 0; i < $_delImg.length; i++){
			$($_delImg[i]).data('pos', i);
		}
		
		$('#photoGallery .delImg').unbind('click');
		$('#photoGallery .delImg').click(function(p_ev){
			var $_thisParent = $(this).parent(); 
			var $_imgID = $($_thisParent).find('img').attr('id');
			var $_pos = $(this).data('pos');
			$.getJSON('/car/ajagfe', 
					{'pid':$_imgID},
					function(p_dat){
						if(p_dat.r == true){
							$('#photoGallery').trigger("removeItem", $_pos);				
							$_this.init_upload_gal();
							//$($_thisParent).detach();
						}	
					});
		});
		
		//Photo options
		$('#photoGallery div a').prettyPhoto({
			slideshow: false,
			theme: 'dark_square'			
		});
		
		$('#photoGallery div').css('margin-right','0.5em');
	};	
}