var link = document.createElement("link");
link.rel= "stylesheet";
link.type = "text/css";
link.href = "//autotunes.de/test/layout.css";
//link.href = "./css/layout.css";
link.media = "all";
document.getElementsByTagName('head')[0].appendChild(link);

function echo(){
//	drawline();
	
	var _label = $("<a class=\"com_advert_img\" title=\"Tag a product\"><img src=\"img/label.png\" /></a>")
					.css({"right":"5px", "width":"20px", "top":"5px"});

	var _wrapper = $("<div class=\"com_advert\"></div>");
	
	$(_wrapper).mouseenter(function(){
		$(this).append(_label);
	}).mouseleave(function(){		
		$(this).find(_label).detach();	
	});
	
	$(_label).click(function(){
		showmenu($(this));
	});	
	$("img").wrap(_wrapper);
};

function drawline(){
	var img1 = $("#img1");
	var img2 = $("#img2");
	var diffX = $(img1).position().left - $(img2).position().left;
	var diffY = $(img1).position().top - $(img2).position().top;
	var length = Math.sqrt(diffX * diffX + diffY * diffY);
	var angle = Math.atan2($(img2).position().top - $(img1).position().top, $(img2).position().left - $(img1).position().left) * 180 / Math.PI;
	
	var transform = "rotate("+angle+"deg)";
	var line = 	$("<div>")
				.appendTo("body")
				.addClass("line")
				.css({
					"position":"absolute",
					"transform":transform
				})
				.width(length)
				.offset({left:$(img1).position().left, top:$(img1).position().top})
				;
};

function showmenu(){
	showmenu(null);
}

function showmenu(p){
	var _menu = $("<div class=\"com_advert_menu\">" +
						"<form class=\"category\">" +
							"<div><label>Saison</label><select name=\"cat_saison\"><option value=\"-1\">Alle</option><option>Ganzjahresreifen</option><option value=\"1\">Sommerreifen</option><option value=\"2\">Winterreifen</option></select></div>" +
							"<div><label>Marke</label><select name=\"cat_brand\"><option>Alle</option><option>Avon</option><option>Bridgestone</option><option>Continental</option></select></div>" +
							"<div><label>Breite</label><select name=\"cat_width\"><option>Alle</option><option>185</option><option>195</option></select></div>" +
							"<div><label>Höhe</label><select name=\"cat_height\"><option>Alle</option><option>25</option><option>30</option></select></div>" +
							"<div><label>Zoll</label><select name=\"cat_inches\"><option>Alle</option><option>15</option><option>16</option></select></div>" +
							"<div><label>Geschwindikeit</label><select><option>Alle</option><option>B (bis 50 km/h)</option><option>E (bis 70 km/h)</option></select></div>" +
							"<div><label>Lastindex</label><select><option>Alle</option><option>20</option><option>22</option></select></div>" +
							"<div><input type=\"button\" value=\"Aktualisieren\" name=\"com_advert_search\"/></div>" +
						"</form>" +
						"<div class=\"content\"></div> " + 
					"</div>");

	_menu = $("<div class=\"com_advert_menu\">" +
						"<div class=\"content\"></div> " + 
					"</div>");
	
	$(_menu).find("input[name=\"com_advert_search\"]").click(function(){
		var cat_saison = $("select[name=\"cat_saison\"]").val();
		var cat_brand = $("select[name=\"cat_brand\"]").val();
		var cat_width = $("select[name=\"cat_width\"]").val();
		var cat_height = $("select[name=\"cat_height\"]").val();
		var cat_inches = $("select[name=\"cat_inches\"]").val();
		$.getJSON('http://localhost:8080/advertising/search',//?jsoncallback=?',
				{"c1":cat_saison
				, "c2":cat_brand
				, "c3":cat_width
				, "c4":cat_height
				, "c5":cat_inches},
				function(p_dat, p_textStatus, p_jqXHR){
					if((p_dat.result == true)){
						alert(p_dat.req1+", "+p_dat.req2+", "+p_dat.req3+", "+p_dat.req4+", "+p_dat.req5);
					}
//					if((p_dat.r == true) && (p_dat.pid != null)){
//						$img = $('#photoGallery').find('img');
//						for($i = 0; $i < $img.length; $i++){
//							if($($img[$i]).attr('id') == p_dat.pid){
//								$('#photoGallery').trigger("removeItem", [$i,false]);
//								break;
//							}
//						}				
//						//$_this.init_upload_gal();
//						//$($_thisParent).detach();
//					}	
				});
	});
	var img = "";
	_img = $("img").each(function(index,element){
		if($(this).attr("src") != undefined)
			img += "<div class=\"img\"><img src=\""+$(this).attr("src")+"\" alt=\""+$(this).attr("src")+"\" title=\""+$(this).attr("src")+"\"/></div>";
	});
	img += "<div></div>";
	$(_menu).find(".content").append(img);
//	$("body").append(_menu);
	$("body").html(_menu);
}

function detectImg(){
//	var _img = $("img").css("border","1px solid black");
	var _menu = "<div class=\"panell\"><form class=\"category\">";
	var _img = $("img").each(function(index,element){
		_menu += "<img src=\""+$(this).attr("src")+"\"/>";
	});
	 _menu += "</div>";
//	 $('body').append(_menu);
}

var js = document.createElement("script");
js.type = "text/javascript";
js.src = "//autotunes.de/test/jquery-1.10.2.js";
//js.src = "./js/jquery-1.10.2.js";
js.onload = function(){
$(document).ready(function(){
//	var e = new echo(); 
	showmenu();
});
	
};
document.getElementsByTagName('head')[0].appendChild(js);