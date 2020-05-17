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
					$.getJSON(g_arr['adp']+'/car/ajagm', 
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
		
		$('#mwst').click(function(p_event){
			val = $(this).attr('checked');
			if (val == true){
				$('#carMwst select[name="mwstSatz"]').removeAttr('disabled');
				$('#carMwst select[name="mwstSatz"]').val('19');
			}else{
				$('#carMwst select[name="mwstSatz"]').val('-1');
				$('#carMwst select[name="mwstSatz"]').attr('disabled','disabled');
			}
		});
		
		var photoUploadBtn = $('.photoUpload');
		if(photoUploadBtn.length > 0){
			new AjaxUpload(	photoUploadBtn, 
							{	
								action: g_arr['adp']+'/car/ajaful', 
								name:'userPhoto',
								data: {'cid':g_arr["CAR_ID"]},
								autoSubmit: true,
								onSubmit: function(file, extension){
									extension = extension.toLowerCase();
									if ((extension != 'jpg')
										&& (extension != 'jpeg')
										&& (extension != 'png')){
										return false;
									}else{
										$date = new Date();
										this.setData({'cid':g_arr["CAR_ID"],'t':$date.getTime()});
										var $_imgPat = $('<li class="item"><img src="/sysPic/loader.gif"/> Upload: '+file+'</li>');
										$_imgPat.data('cid',g_arr["CAR_ID"])
												.data('t',$date.getTime());
										$('.loader').append($_imgPat);
										return true;
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
		
		$('#carInsertForm input[name="carDel"]').click(function(){
			$('#carInsertForm').data('submit', 'carDel');
		});
		
		$('#carInsertForm input[name="carSafe"]').click(function(){
			$('#carInsertForm').data('submit', 'carSafe');
		});
		
		$('#carInsertForm').submit(function(p_ev){
			var _return = true;
			var _submit = $('#carInsertForm').data('submit');
			if ((_submit != null) && (_submit == 'carDel')){
				_return = confirm(g_arr["INFO_4"]);				
			}			
			else if ((_submit != null) && (_submit == 'carSafe')){
				_return = confirm(g_arr["INFO_5"]);				
			}			
			return _return;
		});
		
	};
	
	this.setCM = function(p_dat){
		//if((p_dat != null) && (p_dat.r == true)){
			var $_cont = '<option value="-1">'+g_arr["TXT40"]+'</option>';
			var $_cm;
			if(p_dat.cm != null){			
				for(i = 0; i < p_dat.cm.length; i++){
					$_cm = p_dat.cm[i];
					$_cont += '<option value="'+$_cm.cmid+'">'+$_cm.cmn+'</option>';
				}
			}
			$('select[name="carModel"]').html($_cont);
		//}
	};
	
	this.handlePhotoUpload = function(p_file, p_resp){
		if((p_resp.cid != null) && (p_resp.t != null)){
			$items = $('.loader .item');
			for($i = 0; $i < $items.length; $i++){
				$item = $items[$i];
				$($item).find('img').remove();
				$cid = $($item).data('cid');
				$time = $($item).data('t');
				if(($cid == p_resp.cid) && ($time == p_resp.t)){
					$class = '';
					if(p_resp.r == true){	
						$class = 'green';
					}else{
						$class = 'red';
					}
					$($items).addClass($class)							
							.fadeOut(2000,function(){
								$(this).remove();
							});
					break;
				}
			}
		}
		
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
			$.getJSON(g_arr['adp']+'/car/ajagfe', 
					{'pid':$_imgID},
					function(p_dat){
						if((p_dat.r == true) && (p_dat.pid != null)){
							$img = $('#photoGallery').find('img');
							for($i = 0; $i < $img.length; $i++){
								if($($img[$i]).attr('id') == p_dat.pid){
									$('#photoGallery').trigger("removeItem", [$i,false]);
									break;
								}
							}				
							//$_this.init_upload_gal();
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
	/*
	this.init_upload_gali = function(){
		$('#photoGallery .delImg').click(function(p_ev){
			var $_thisParent = $(this).parent(); 
			var $_imgID = $($_thisParent).find('img').attr('id');
			$.getJSON(g_arr['adp']+'/car/agfe', 
					{'pid':$_imgID},
					function(p_dat){
						if(p_dat.r == true){
							$($_thisParent).detach();
						}	
					});
		});
	};*/
}