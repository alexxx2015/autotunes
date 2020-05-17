$(document).ready(function(){
	var v_cs = new cs();
	v_cs.init();
});

function cs(){
	var _this;
	
	this.init = function(){
		_this = this;
		$('.brandB input.carBrand').click(function(p_ev){
			if($(this).is(':checked')){
				cb = this;
				if($(this).data('cid') != null){
					_this.setCM($(this).data('cid'), cb);
				}
				else{
					cid = $(this).attr('value');
					var adminPath = '/'+g_arr["ADMIN_MOD_PATH"];
					$.getJSON(adminPath+'/car/ajagm', 
							{'cid':cid},
							function(p_dat){
								if(p_dat.r == true){
									$(cb).data('cid', p_dat);
									_this.setCM(p_dat, cb);
								}	
							});
				}
				
			}
			else{
				$(this).parent().find('.model').remove();
			}
		});
		
		$('#carSortB select[name="carSort"]').change(function(p_event){
			val = $(this).val();
			if (val != -1){
				$('#carSortB select[name="carSortOpt"]').removeAttr('disabled');
			}else{
				$('#carSortB select[name="carSortOpt"]').attr('disabled','disabled');
			}
		});
		
		$('#carPLZB input[name="carPLZ"]').keyup(function(p_event){
			val = $(this).val();
			if (val.length > 0){
				$('#carPLZB select[name="carCC"]').removeAttr('disabled');
			}else{
				$('#carPLZB select[name="carCC"]').attr('disabled','disabled');
			}
		});
		
		$('html,body').scrollTop(0);
		
		_this.initNextSearchRes();
	};
	
	this.setCM = function(p_dat, p_cb){
		if((p_dat != null) && (p_dat.r == true)){
			
			cont = '<ul class="model">';
			for(i = 0; i < p_dat.cm.length; i++){
				cm = p_dat.cm[i];
				cont += '<li class="l'+cm.cml+'"><input type="checkbox" name="carModel[]" value="'+cm.cmid+'"/>'+cm.cmn+'</li>';
			}
			cont += '</ul>';
			$(p_cb).parent().append(cont);
		}
	};
	
	this.initNextSearchRes = function(){
		$('.nextSearchResult a').click(function(p_event){
			var rs= $(this).parent().find('input').val();
			var ars = $('.search_res').find('.search_res_entry');
			$.getJSON('/car/ajagnsr', 
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
		var _ca;
		var ca;
		var imgPath;
		var carPic;
		
		var ars = $('.search_res').find('.search_res_entry');
		
		var page = ars.length + p_data.ca.length;
		
		for(var i = 0; i < p_data.ca.length; i++){
			ca = p_data.ca[i];
			
			imgPath = gl_param["noPicURL"];			
			if(ca.carPics.length){
				carPic = ca.carPics[0];
				imgPath = gl_param["picPath"]+'/'+ca['carID']+'_'+carPic['vPicID']+'.jpeg';
			}
			
			
			_ca = gl_param["sret"];
			_ca = _ca.replace(/p1/,imgPath);
			_ca = _ca.replace(/p2/,'/car/'+ca['carID']+'/'+page);
			_ca = _ca.replace(/p3/, ca['carBrandName']+' '+ca['carModelName']);
			$('.search_res').append(_ca);
		}
	}
}