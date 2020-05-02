function __autocomplete() {
        
	var __this = this;

	this.init = function(id, url, idS){
		$(id).autocomplete({
			source: url,
			select: function(e, o){
				__this.fillOptions(idS, o.item.ID)
				//alert(o.item.ID);
				
			}
		});	
	};
	
	this.fillOptions = function(id, idC) {
	   var url2 = 'php/autocomplete.php?idC='+idC+'&aktion=readStationTo';	
	   //console.log(idC);
	   $(id).autocomplete({
			source: url2,
			select: function(e, o) {
			  //alert(o.item.ID);
			}
		});		
	};
}