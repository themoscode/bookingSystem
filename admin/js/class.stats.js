function __stats() {

	this.loadEvents = function(){
		
		$('#statsHeader').click(function(){
			setField('stats');$('#homePage').hide();
		});
		
		$('#webalizerHeader').click(function(){
			setField('webalizer');
		});
		
		$('#clickCounterHeader').click(function(){
			setField('clickCounter');
		});
		
		
	}


}