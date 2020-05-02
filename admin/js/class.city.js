function __city(){
	
	var __this = this;
	
	this.loadEvents = function(){
	
		cityList = document.getElementById("cityList");
		cityList.onchange = function(){
			
			myStation.getStationList();
			myStation.checkStationControls();
		}
		cityHeader = document.getElementById("cityHeader");
		cityHeader.onclick = function() {setField('city');$('#homePage').hide();};
		
		
		//cityMainButtons = document.getElementById("cityMainButtons");
		
		addCity = document.getElementById("addCity");
		addCity.onclick = __this.showAddCity;
		
		delCity = document.getElementById("delCity");
		delCity.onclick = __this.getDelCity;
		
		editCity = document.getElementById("editCity");
		editCity.onclick = __this.showEditCity;
		
		//addCityField = document.getElementById("addCityField");
		cityName = document.getElementById("cityName");
		submitNewCity = document.getElementById("submitNewCity");
		submitNewCity.onclick = __this.getSubmitNewCity;
		
		cancelNewCity = document.getElementById("cancelNewCity");
		cancelNewCity.onclick = __this.hideAddCity;
		
		//editCityField = document.getElementById("editCityField");
		cityNameEdit = document.getElementById("cityNameEdit");
		submitEditCity = document.getElementById("submitEditCity");
		submitEditCity.onclick = __this.getSubmitEditCity;
		
		cancelEditCity = document.getElementById("cancelEditCity");
		cancelEditCity.onclick = __this.hideEditCity;
	}
	
	this.showAddCity=function(){
		
		setFieldOff('cityMain');
		setFieldOn('addCityField');
		addStation.disabled=true;
		editStation.disabled=true;
		delStation.disabled=true;
		stationList.disabled=true;
		//model.value="";
		//numberOfSeats.value="";
	}
	
	this.hideAddCity = function(){
		
		setFieldOff('addCityField');
		setFieldOn('cityMain');
		addStation.disabled=false;
		editStation.disabled=false;
		delStation.disabled=false;
		stationList.disabled=false;
		
	}
	
	this.showDelCity = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				
				__this.getCityList();
				cityList.selectedIndex=0; 
				myStation.getStationList();
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				
				}
			
		}
		
	}
	
	this.getDelCity=function(){
		
		x = cityList.selectedIndex;
		ID = cityList[x].value;
		selVal =  cityList[x].text;
		
		if (ID!=0){
		
			var r=confirm("Wollen Sie die Stadt ("+selVal+") wirklich löschen?" );
			
			if (r==true) {
				var ajaxCon = myAjax.connect();
				ajaxCon.open("POST","php/delcity.php",true);
				ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajaxCon.onreadystatechange = function(){__this.showDelCity(ajaxCon);};
				
				ajaxCon.send("ID="+ID);
			}
		}
	
	}
	
	this.showEditCity = function(){
	
		var x = cityList.selectedIndex;
		var ID = cityList[x].value;
		var name = cityList[x].text;
		var used = cityList[x].getAttribute('data-used');
		
		if (ID!=0) {
			
			if (used === 'true') {
		
				alert('Für diese Stadt gibt es bereits eingetragene Fahrten. Städte mit eingetragenen Fahrten können nicht verändert werden.');
				return;
		
			}
			
			cityNameEdit.value = name;
			
			setFieldOff('cityMain');
			setFieldOn('editCityField');
		
		}
	}
	
	this.hideEditCity = function(){
		
		setFieldOff('editCityField');
		setFieldOn('cityMain');
	}
	
	this.getSubmitNewCity = function(){
		
		name=cityName.value;
	
		if (name.fulltrim()!="") {
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/submitnewcity.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function(){__this.showSubmitNewCity(ajaxCon);};
			
			ajaxCon.send("name="+name);
			
			}
		else {
			alert("Bitte geben Sie einen gültigen Stadtnamen ein.");
		}
	}
	
	this.showSubmitNewCity = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				__this.getCityList();
				cityList.selectedIndex=0;
				myStation.getStationList();
				__this.hideAddCity(); 
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				
				}	
		}	
	}
	
	this.getSubmitEditCity = function(){
		
			x = cityList.selectedIndex;
			ID = cityList[x].value;
			selVal =  cityList[x].text;
			name=cityNameEdit.value;
			
			
			if (ID!=0 && name.fulltrim()!=''){
				
				//var r=confirm("Wollen Sie die Stadt ("+selVal+") wirklich bearbeiten?" );
				
				//if (r==true) {
					var ajaxCon = myAjax.connect();
					ajaxCon.open("POST","php/editcity.php",true);
					ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajaxCon.onreadystatechange = function(){__this.showSubmitEditCity(ajaxCon);};
					ajaxCon.send("ID="+ID+"&name="+name);
				//}
			}
			
			else {
					alert("Bitte geben Sie einen gültigen Stadtnamen ein.");
			}

	}
	
	this.showSubmitEditCity = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				
				//cityList.innerHTML=json.result;
				__this.getCityList();
				cityList.selectedIndex=0;
				__this.hideEditCity();
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				
				}
			
		}
	
	}
	
	this.showCityList = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			
			if (json.result != "0"){
				
				cityList.innerHTML=json.result;
				__this.loadEvents();
				myStation.getStationList();
				myStation.checkStationControls();
				}	
		}

	}
	
	this.getCityList = function(){
		
		//alert("hi");
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/citylist.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){__this.showCityList(ajaxCon);};
		ajaxCon.send();
	
	}

}