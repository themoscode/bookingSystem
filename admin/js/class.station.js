
function __station(){

	var __this = this;
	this.loadEvents = function(){
		
		stationList = document.getElementById("stationList");
		stationList.onchange = __this.checkStationControls;
	
		addStation = document.getElementById("addStation");
		addStation.onclick = __this.showAddStation;
		
		editStation = document.getElementById("editStation");
		editStation.onclick = __this.showEditStation;
		
		cancelEditStation = document.getElementById("cancelEditStation");
		cancelEditStation.onclick = __this.hideEditStation;
		
		cancelNewStation = document.getElementById("cancelNewStation");
		cancelNewStation.onclick = __this.hideAddStation;
		
		delStation = document.getElementById("delStation");
		delStation.onclick = __this.getDelStation;
		
		submitEditStation=document.getElementById("submitEditStation");
		submitEditStation.onclick = __this.getSubmitEditStation;
		
		submitNewStation = document.getElementById("submitNewStation");
		submitNewStation.onclick = __this.getSubmitNewStation;
		
		stationName = document.getElementById("stationName");
	}
	
	this.checkStationControls = function(){
		
		if (cityList.selectedIndex !=0 && stationList.selectedIndex !=0) {
		
			editStation.disabled=false;
			delStation.disabled=false;
		}
		else {		
				editStation.disabled=true;
				delStation.disabled=true;
	    	 }
		
	}
	
	this.getSubmitNewStation=function(){
		
		var y=cityList.selectedIndex;
		var cityID = cityList[y].value;
		//cityText = cityList[y].text;
		var name = stationName.value;
		
		if (name.fulltrim()!='') {
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/submitnewstation.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){__this.showSubmitNewStation(ajaxCon);};
		
		ajaxCon.send("cityID="+cityID+"&name="+name);
		
		//alert('hhh');
		}
		else{
			alert('Bitte geben Sie einen gültigen Namen für die Haltestelle ein.')
		}
	}
	
	this.showSubmitNewStation=function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			//alert(json.result);
			
			if (json.result != "0"){
				
				//testArea.innerHTML=ajaxCon.responseText;
				__this.getStationList();
				__this.hideAddStation();
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////			
			}
			
		}
		
	}
	
	this.showAddStation = function(){
		
		var ID = cityList[cityList.selectedIndex].value;
		//alert(ID);
		if (ID!=0) {
			setFieldOff('stationMain');
			setFieldOn('addStationField');
			cityList.disabled = true;
			addCity.disabled = true;
			editCity.disabled = true;
			//delCity.disabled = true;
			stationName.value="";
			
		}
	}
	
	this.hideAddStation = function(){
		
		setFieldOff('addStationField');
		setFieldOn('stationMain');
		cityList.disabled = false;
		addCity.disabled = false;
		editCity.disabled = false;
		//delCity.disabled = false;
		
	}
	
	this.showSubmitEditStation = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				
				__this.getStationList();
				__this.hideEditStation();
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				}
			
		}
		
	}
	
	this.getSubmitEditStation = function(){
		
			x = stationList.selectedIndex;
			ID = stationList[x].value;
			selVal =  stationList[x].text;
			
			//cityList = document.getElementById("");
			y=cityList.selectedIndex;
			cityID = cityList[y].value;
			cityText = cityList[y].text;
			
			name = stationNameEdit.value;
			
			//alert("cityID="+cityID+",ID="+ID+",city="+cityText+",station="+selVal+",name="+name);
	
			
			if (ID!=0 && cityID !=0 && name.fulltrim()!=''){
				
				//var r=confirm("Wollen Sie die Haltestelle ("+selVal+") wirklich bearbeiten?" );
				
				//if (r==true) {
					
					//return;
					var ajaxCon = myAjax.connect();
					ajaxCon.open("POST","php/editstation.php",true);
					ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajaxCon.onreadystatechange = function(){__this.showSubmitEditStation(ajaxCon);};
				    
					ajaxCon.send("ID="+ID+"&cityID="+cityID+"&name="+name);
					//alert(ajaxCon);
				
				//}
			}
			else {
				alert("Bitte geben Sie einen gültigen Namen für die Haltestelle ein.");
			}
			
	}
	
	this.showDelStation = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				//alert(json.result);
				//testArea.innerHTML=json.result;
				stationList.innerHTML=json.result;
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				//alert(json.result);
				}
			
			
		}
	}
	
	this.getDelStation = function(){
		
		
		x = stationList.selectedIndex;
		ID = stationList[x].value;
		selVal =  stationList[x].text;
		
		//cityList = document.getElementById("");
		y=cityList.selectedIndex;
		cityID = cityList[y].value;
		cityText = cityList[y].text;
		
		//alert("cityID="+cityID+",ID="+ID+",city="+cityText+",station="+selVal);
		//alert(cityList[y].text);
		
		if (ID!=0){
		
			var r=confirm("Wollen Sie die Haltestelle ("+selVal+") wirklich löschen?" );
			
			if (r==true) {
				var ajaxCon = myAjax.connect();
				ajaxCon.open("POST","php/delstation.php",true);
				ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajaxCon.onreadystatechange = function(){__this.showDelStation(ajaxCon);};
				
				ajaxCon.send("ID="+ID+"&cityID="+cityID);
			}
		}
	}
	
	this.hideEditStation = function(){
		
		
		//alert("xxxx");
		setFieldOff('editStationField');
		setFieldOn('stationMain');
		
		cityList.disabled = false;
		addCity.disabled = false;
		editCity.disabled = false;
		//delCity.disabled = false;
		
	}
	
	this.showEditStation = function(){
		
		var x = stationList.selectedIndex;
		var ID = stationList[x].value;
		var used = stationList[x].getAttribute('data-used');
		
		if (ID!=0) {
			
			if (used === 'true') {
		
				alert('Für diese Haltestelle gibt es bereits eingetragene Fahrten. Haltestellen mit eingetragenen Fahrten können nicht verändert werden.');
				return;
		
			}
			
			var selVal =  stationList[x].text;
			stationNameEdit.value = selVal;
			
			setFieldOff('stationMain');
			setFieldOn('editStationField');
			
			cityList.disabled = true;
			addCity.disabled = true;
			editCity.disabled = true;
			//delCity.disabled = true;
		
		}
	}
	
	this.getStationList = function(event){
		
		//cityID = event.target.value;
	
		var cityID = cityList[cityList.selectedIndex].value;
		
		if (cityList.selectedIndex ==0) {
		
			addStation.disabled=true;
			stationList.disabled=true;
			
			editCity.disabled=true;
			delCity.disabled=true;
		
		}
		
		else {
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/stationlist.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function(){__this.showStationList(ajaxCon);};
			ajaxCon.send("cityID="+cityID);
		
			editStation.disabled=true;
			delStation.disabled=true;
			
			addStation.disabled=false;
			stationList.disabled=false;
			
			editCity.disabled=false;
			delCity.disabled=false;
		
		}
		
		
	}
	
	this.showStationList = function(ajaxCon){
	
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			//alert(myAjax.responseText);
			//stationList.innerHTML = ajaxCon.responseText; 
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			if (json.result != "0"){
				
				stationList.innerHTML=json.result;
				__this.loadEvents();
				//alert(stationList.innerHTML);
			}
				
		}

	}

}
