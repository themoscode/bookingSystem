function __timetable(){
	
	var __this = this;
	
	this.loadEvents = function(){
		
		$('#timeTableHeader').click(function(){
			setField('timeTable');$('#homePage').hide();
		});
		
		$('#bookingTableHeader').click(function(){
			
			$('#bookingTable').slideToggle( "slow" );$('#homePage').hide();
			
		});
	
	}
	
	this.getTimeTableList = function(){
		
		if (arguments.length>0) {
			
			var searchDateFrom_obj = document.getElementById('searchDateFromTT');
			var searchDateFrom = searchDateFrom_obj.value;
			
			var searchDateTo_obj = document.getElementById('searchDateToTT');
			var searchDateTo = searchDateTo_obj.value;
			
			if (isValidDate(searchDateFrom_obj) === false || isValidDate(searchDateTo_obj) === false) {
			
				alert("Bitte geben Sie ein gültiges Zeitformat an:[dd-mm-yyyy]"); 
				return false;
			
			}
			
			
		}
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/timetablelist.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){__this.showTimeTableList(ajaxCon);};
		
		if (arguments.length>0) {ajaxCon.send("searchDateFrom="+searchDateFrom+"&searchDateTo="+searchDateTo);}
		else {ajaxCon.send();}

	}
	
	this.showTimeTableList = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
				timeTable.innerHTML=ajaxCon.responseText;
				showDatePickerForClassName('dateField_timetable');
				
				
		}

	}

}