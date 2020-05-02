

function __slideshow(){

	this.hide = function(){
	
		if ($('#slides').length > 0 ) {
			$('#slides').remove();
		}
	}
	
	
	this.show = function (){
		
		if (_SLIDESTATUS === 'off') {return;}
		
		
		if ($('#slides').length === 0 ) {
			
			var str = '';
			
			str+='<div id="slides">';
			str+='<img src="images/img-city-slide/frankfurt-berlin.png">';
			str+='<img src="images/img-city-slide/wiesbaden-berlin.png">';
			str+='<img src="images/img-city-slide/berlin-frankfurt.png">';
			str+='<img src="images/img-city-slide/berlin-wiesbaden.png">';
			str+='</div>';
			$('#content').prepend(str);
		}
		
		$('#slides').slidesjs({
			width: 592,
			height: 237,
			play: {
				active: true,
				auto: true,
				interval: 4000,
				swap: true
				}
		
			});
		
	}
	
}