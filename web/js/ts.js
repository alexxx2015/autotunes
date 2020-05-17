$(document).ready(function(){
	var v_ts = new ts();
	v_ts.init();
});

function ts(){
	var _this;
	
	this.init = function(){
		_this = this;
		/*
		$('.brandB input').click(function(p_ev){
			if($(this).is(':checked')){
				tb = this;
				if($(this).data('tid') != null){
					_this.setTM($(this).data('tid'), tb);
				}
				else{
					tid = $(this).attr('value');
					$.getJSON('/truck/ajagm', 
							{'tid':tid},
							function(p_dat){
								if(p_dat.r == true){
									$(tb).data('tid', p_dat);
									_this.setTM(p_dat, tb);
								}	
							});
				}				
			}
			else{
				$(this).parent().find('.model').remove();
			}
		});
		*/
		
		$('#truckSortB select[name="truckSort"]').change(function(p_event){
			val = $(this).val();
			if (val != -1){
				$('#truckSortB select[name="truckSortOpt"]').removeAttr('disabled');
			}else{
				$('#truckSortB select[name="truckSortOpt"]').attr('disabled','disabled');
			}
		});
		
		$('#truckPLZB input[name="truckPLZ"]').keyup(function(p_event){
			val = $(this).val();
			if (val.length > 0){
				$('#truckPLZB select[name="truckCC"]').removeAttr('disabled');
			}else{
				$('#truckPLZB select[name="truckCC"]').attr('disabled','disabled');
			}
		});
		
		$('html,body').scrollTop(0);
		
		_this.initNextSearchRes();
	};
	
	this.setTM = function(p_dat, p_tb){
		if((p_dat != null) && (p_dat.r == true)){
			
			cont = '<ul class="model">';
			for(i = 0; i < p_dat.tm.length; i++){
				tm = p_dat.tm[i];
				cont += '<li class="l'+tm.tml+'"><input type="checkbox" name="truckModel[]" value="'+tm.tmid+'"/>'+tm.tmn+'</li>';
			}
			cont += '</ul>';
			$(p_cb).parent().append(cont);
		}
		
		/*
		if((p_dat != null) && (p_dat.r == true)){
			cont = '<ul class="model">';
			for(i = 0; i < p_dat.tm.length; i++){
				tm = p_dat.tm[i];

				cont += '<li class="l'+tm.tml+'"><input type="checkbox" name="truckModel[]" value="'+tm.tmid+'"/>'+tm.tmn+'</li>';
			}
			cont += '</ul>';
			$(p_tb).parent().append(cont);
		}
		*/
	};
	
	this.initNextSearchRes = function(){
		$('.nextSearchResult a').click(function(p_event){
			var rs= $(this).parent().find('input').val();
			var ars = $('.search_res').find('.search_res_entry');
			$.getJSON('/truck/ajagnsr', 
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
		var truckPic;
		
		var ars = $('.search_res').find('.search_res_entry');
		
		var page = ars.length + p_data.ca.length;
		
		for(var i = 0; i < p_data.ca.length; i++){
			ca = p_data.ca[i];
			
			imgPath = gl_param["noPicURL"];			
			if(ca.truckPics.length){
				truckPic = ca.truckPics[0];
				imgPath = gl_param["picPath"]+'/'+ca['truckID']+'_'+truckPic['vPicID']+'.jpeg';
			}
			
			
			_ca = gl_param["sret"];
			_ca = _ca.replace(/p1/,imgPath);
			_ca = _ca.replace(/p2/,'/truck/'+ca['truckID']+'/'+page);
			_ca = _ca.replace(/p3/, ca['truckBrandName']+' '+ca['truckModelName']);
			$('.search_res').append(_ca);
		}
	}
}