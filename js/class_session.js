function __session(){

	var __this = this;
	var __myTimer = '';
	
	
	this.init = function(){
		
		var $data = '';
		if (__myTimer!=='') { clearInterval(__myTimer);}
	
		$.post('php/session.php?action=init', $data , function(result) {
				
				if (result.initialized === true) {
						
						//console.log('session initialized');
						//engine.msg('session initialized');
						
						__this.check();
						
					}
				
		},'json');
	
	
	}
	this.stop = function(){
		
		clearInterval(__myTimer);
	
	}
	
	this.check = function(){
		
		__myTimer = setInterval(function(){checkSession()}, 1000);
		//checkSession();
	
	}

	function checkSession() {
		
		//console.log('check session');
		
		var $data = '';
		
		$.post('php/session.php?action=check', $data , function(result) {
				
				if (result.expired ===true) {
						
						//console.log('session expired');
						engine.msg('Ihre Sitzung ist abgelaufen. Bitte haben Sie Verständnis dafür, dass wir Ihre Platzanfrage max. für 15 min reservieren können.');
						basket.clear();
					    booking.hide();
						search.show();
						//slideshow.hide();
						__this.init();
					}
		},'json');
	
	}
	
	

}