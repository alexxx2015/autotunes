$(document).ready(function(){
	var v_ti = new ti();
	v_ti.init();
});

function ti(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		
		$('select[name="truckBrand"]').change(function(p_ev){
				if(!$.isArray($(this).data('tid'))){
					$(this).data('tid',new Array());
				}
				
				var $_tid = $(this).val();
				var $_tb = this;
				var $_data = $(this).data('tid');
				if(($_data[$_tid] != null) && ($_data[$_tid] != undefined)){
					$_this.setTM($_data[$_tid]);
				}
				else{
					$.getJSON('/truck/ajagm', 
							{'tid':$_tid},
							function(p_dat){
								if(p_dat.r == true){
									$_data[$_tid] = p_dat;
									$($_tb).data('tid', $_data);
								}	
								$_this.setTM(p_dat);
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
		
		$('textarea[name="truckDesc"]').keyup(function(p_ev){
			var $_maxLength = 1000;
			var $_val = $(this).val();
			if($_val.length > $_maxLength){
				$_val = $_val.substr(0, $_maxLength);
				$(this).val($_val);
			}
			$('#truckDescChars').html($_val.length);
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
				$('#truckMwst select[name="mwstSatz"]').removeAttr('disabled');
				$('#truckMwst select[name="mwstSatz"]').val('19');
			}else{
				$('#truckMwst select[name="mwstSatz"]').val('-1');
				$('#truckMwst select[name="mwstSatz"]').attr('disabled','disabled');
			}
		});
		
		var photoUploadBtn = $('.photoUpload');
		if(photoUploadBtn.length > 0){
			new AjaxUpload(	photoUploadBtn, 
							{	
								action: '/truck/ajaful', 
								name:'userPhoto',
								data: {'tid':g_arr["TRUCK_ID"]},
								autoSubmit: true,
								onSubmit: function(file, extension){
									extension = extension.toLowerCase();
									if ((extension != 'jpg')
										&& (extension != 'jpeg')
										&& (extension != 'png')){
										return false;
									}else{
										$date = new Date();
										this.setData({'tid':g_arr["TRUCK_ID"],'t':$date.getTime()});
										var $_imgPat = $('<li class="item"><img src="/sysPic/loader.gif"/> Upload: '+file+'</li>');
										$_imgPat.data('tid',g_arr["TRUCK_ID"])
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
			action: '/truck/aful',
			allowedExtensions: ['jpg', 'jpeg', 'png'],
			onSubmit: function(id, filename){},
			onComplete: function(id, filename, resp){
				$_this.photoComplete(id, filename, resp);
			}
		});*/
		
	};
	
	this.setTM = function(p_dat){
		//if((p_dat != null) && (p_dat.r == true)){
			var $_cont = '<option value="-1">'+g_arr['TXT40']+'</option>';
			var $_tm;
			if (p_dat.tm != null){
				for(i = 0; i < p_dat.tm.length; i++){
					$_tm = p_dat.tm[i];
					$_cont += '<option value="'+$_tm.tmid+'">'+$_tm.tmn+'</option>';
				}
			}
			$('select[name="truckModel"]').html($_cont);
		//}
	};
	
	this.handlePhotoUpload = function(p_file, p_resp){		
		if((p_resp.tid != null) && (p_resp.t != null)){
			$items = $('.loader').find('.item');
			for($i = 0; $i < $items.length; $i++){
				$item = $items[$i];
				$($item).find('img').remove();
				$tid = $($item).data('tid');
				$time = $($item).data('t');
				if(($tid == p_resp.tid) && ($time == p_resp.t)){
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
			$.getJSON('/truck/ajagfe', 
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
		$_delImg = $('#photoGallery .delImg');
		$('#photoGallery').trigger('slideTo',$_delImg.length-1);
	};
}