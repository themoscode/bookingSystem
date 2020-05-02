function __engine(){

	var __this = this;
	var __myTimer = '';
	
	
	this.msg = function (txt){
	
		if ($('#myDialog').length === 0 ) {
			$('body').prepend('<div id="myDialog">'+txt+'<span>X</span></div>');
			
			$('#myDialog').click(function(){
				$(this).hide('slow');
			});
			
		}
		$("html, body").animate({ scrollTop: 0 }, "slow" , function(){
			$('#myDialog').show('slow');
			__myTimer = setTimeout(function(){__this.msgHide()}, 5000);
			
		});
		
	}
	
	this.msgHide = function(){
		if ($('#myDialog').length > 0 ) {
			$('#myDialog').hide('slow');
		}
		if (__myTimer!=='') { clearInterval(__myTimer);}
	}
	
	
	
	this.msg_main = function (txt){
			
			var html = '<div id="bookingConfirmMessage">'+txt+'</div>';
			$( "#booking" ).html(html);
			$( "#booking" ).show('slow');
		   
	}
	
	
	this.start = function(){
	
			datepicker = new __datepicker();
			datepicker.init();
			
			slideshow = new __slideshow();
		    slideshow.show();
						
			autocomplete = new __autocomplete();
			autocomplete.init('#from-city-search','php/autocomplete.php?aktion=readStationFrom','#to-city-search');	
			
			basket = new __basket();
			basket.clearRemains();
			
			search = new __search();
			search.init();
			
			booking = new __booking();
			
			session = new __session();
			
			//$('ul.sf-menu').sooperfish();
			//$('.top').click(function () { $('html, body').animate({ scrollTop: 0 }, 'fast'); return false; });
	
	}

}