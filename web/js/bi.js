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
					$.getJSON('/bike/ajagm', 
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
		
		$('#mwst').click(function(p_event){
			val = $(this).attr('checked');
			if (val == true){
				$('#bikeMwst select[name="mwstSatz"]').removeAttr('disabled');
				$('#bikeMwst select[name="mwstSatz"]').val('19');
			}else{
				$('#bikeMwst select[name="mwstSatz"]').val('-1');
				$('#bikeMwst select[name="mwstSatz"]').attr('disabled','disabled');
			}
		});
		
		var photoUploadBtn = $('.photoUpload');
		if(photoUploadBtn.length > 0){
			new AjaxUpload(	photoUploadBtn, 
							{	
								action: '/bike/ajaful', 
								name:'userPhoto',
								data: {'bid':g_arr["BIKE_ID"]},
								autoSubmit: true,
								onSubmit: function(file, extension){
									extension = extension.toLowerCase();
									if ((extension != 'jpg')
										&& (extension != 'jpeg')
										&& (extension != 'png')){
										return false;
									}else{
										$date = new Date();
										this.setData({'bid':g_arr["BIKE_ID"],'t':$date.getTime()});
										var $_imgPat = $('<li class="item"><img src="/sysPic/loader.gif"/> Upload: '+file+'</li>');
										$_imgPat.data('bid',g_arr["BIKE_ID"])
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
		
		//Bikeousel options
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
			action: '/bike/aful',
			allowedExtensions: ['jpg', 'jpeg', 'png'],
			onSubmit: function(id, filename){},
			onComplete: function(id, filename, resp){
				$_this.photoComplete(id, filename, resp);
			}
		});*/
		
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
		//{"r":true,"h":"1223","tu":"\/pic\/c_13_1223.jpeg"}
		//<div><span  class="delImg">X</span><a rel="prettyPhoto[pp_gal]" href="/pic/c_13_1225.jpeg" ><img src="p1" id="1225"/></a></div>
		
		if((p_resp.bid != null) && (p_resp.t != null)){
			$items = $('.loader').find('.item');
			for($i = 0; $i < $items.length; $i++){
				$item = $items[$i];
				$($item).find('img').remove();
				$bid = $($item).data('bid');
				$time = $($item).data('t');
				if(($bid == p_resp.bid) && ($time == p_resp.t)){
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
			$_img = $_img.replace(/p2/g, p_resp.h);
			$('#photoGallery').trigger("insertItem", [$_img,"end",true]);
			$_this.init_upload_gal();
		}
	};
	
	this.init_upload_gal = function(){		
		$('#photoGallery .delImg').unbind('click');
		$('#photoGallery .delImg').click(function(p_ev){
			var $_thisParent = $(this).parent(); 
			var $_imgID = $($_thisParent).find('img').attr('id');
			$.getJSON('/bike/ajagfe', 
					{'pid':$_imgID},
					function(p_dat){
						if((p_dat.r == true) && (p_dat.pid != null)){
							$img = $('#photoGallery').find('img');					
							for($i = 0; $i < $img.length; $i++){
								if($($img[$i]).attr('id') == p_dat.pid){
									//alert($i+":"+$($img[$i]).attr('id')+":"+p_dat.pid);
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
		$_delImg = $('#photoGallery .delImg');
		$('#photoGallery').trigger('slideTo',$_delImg.length-1);
	};	
}