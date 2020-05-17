$(document).ready(function(){
	var _menu = new menu();
	_menu.init();
});

function menu(){
	var $_this;
	
	this.init = function(){
		$_this = this;
		
		//$(".submenu").parent().prepend("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)

	    $("#mainMenu li a").mouseover(function() { //When trigger is clicked...
	        //Following events are applied to the subnav itself (moving subnav up and down)  
	        $(this).parent().find(".submenu").slideDown('fast').show(); //Drop down the subnav on click  
	  
	        $(this).parent().hover(function() {  
	        }, function(){  
	            $(this).parent().find(".submenu").slideUp('slow'); 
	        });  
	   
	        }).hover(function() {  
	            $(this).addClass("subhover"); 
	        }, function(){ 
	            $(this).removeClass("subhover"); 
	            
	    });
	};
}