$(document).ready(function(){
	var v_bs = new bs();
	v_bs.init();
});

function bs(){
	var _this;
	
	this.init = function(){
		_this = this;
		$('.brandB input.bikeBrand').click(function(p_ev){
			if($(this).is(':checked')){
				bb = this;
				if($(this).data('bid') != null){
					_this.setBM($(this).data('bid'), bb);
				}
				else{
					bid = $(this).attr('value');
					var adminPath = '/'+g_arr["ADMIN_MOD_PATH"];
					$.getJSON(adminPath+'/bike/ajagm', 
							{'bid':bid},
							function(p_dat){
								if(p_dat.r == true){
									$(bb).data('bid', p_dat);
									_this.setBM(p_dat, bb);
								}	
							});
				}				
			}
			else{
				$(this).parent().find('.model').remove();
			}
		});
		
		$('#bikeSortB select[name="bikeSort"]').change(function(p_event){
			val = $(this).val();
			if (val != -1){
				$('#bikeSortB select[name="bikeSortOpt"]').removeAttr('disabled');
			}else{
				$('#bikeSortB select[name="bikeSortOpt"]').attr('disabled','disabled');
			}
		});
		
		$('#bikePLZB input[name="bikePLZ"]').keyup(function(p_event){
			val = $(this).val();
			if (val.length > 0){
				$('#bikePLZB select[name="bikeCC"]').removeAttr('disabled');
			}else{
				$('#bikePLZB select[name="bikeCC"]').attr('disabled','disabled');
			}
		});
		
		$('html,body').scrollTop(0);
		
		_this.initNextSearchRes();
	};
	
	this.setBM = function(p_dat, p_bb){
		if((p_dat != null) && (p_dat.r == true)){
			
			cont = '<ul class="model">';
			for(i = 0; i < p_dat.bm.length; i++){
				bm = p_dat.bm[i];
				cont += '<li class="l'+bm.bml+'"><input type="checkbox" name="bikeModel[]" value="'+bm.bmid+'"/>'+bm.bmn+'</li>';
			}
			cont += '</ul>';
			$(p_bb).parent().append(cont);
		}
	};
	
	this.initNextSearchRes = function(){
		$('.nextSearchResult a').click(function(p_event){
			var rs= $(this).parent().find('input').val();
			var ars = $('.search_res').find('.search_res_entry');
			$.getJSON('/bike/ajagnsr', 
						{'rs':rs,
						'ars':ars.length
						},
						function(p_data){
							if((p_data.r == true) && (p_data.ca.length > 0)){
								_this.handleNextSearchRes(p_data);
							}
							else{
								$('.nextSearchResult a').unbind('click');
								$('.nextSearchResult').detach();
							}
						});
		});
	};
	
	this.handleNextSearchRes = function(p_data){
		var _ba;
		var ba;
		var imgPath;
		var bikePic;
		
		var ars = $('.search_res').find('.search_res_entry');
		
		var page = ars.length + p_data.ba.length;
		
		for(var i = 0; i < p_data.ba.length; i++){
			ba = p_data.ba[i];
			
			imgPath = gl_param["noPicURL"];			
			if(ba.bikePics.length){
				bikePic = ba.bikePics[0];
				imgPath = gl_param["picPath"]+'/'+ba['bikeID']+'_'+bikePic['vPicID']+'.jpeg';
			}
			
			
			_ba = gl_param["sret"];
			_ba = _ba.replace(/p1/,imgPath);
			_ba = _ba.replace(/p2/,'/bike/'+ba['bikeID']+'/'+page);
			_ba = _ba.replace(/p3/, ba['bikeBrandName']+' '+ba['bikeModelName']);
			$('.search_res').append(_ba);
		}
	}
}