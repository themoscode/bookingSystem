function __bus(){

	var __this = this;
	
	this.loadEvents = function(){
		
		busList = document.getElementById("busList");
		busList.onchange = __this.checkBusControls;
	
		addBus = document.getElementById("addBus");
		addBus.onclick = __this.showAddBus;
		
		editBus = document.getElementById("editBus");
		editBus.onclick = __this.showEditBus;
		
		delBus = document.getElementById("delBus");
		busCodeEdit = document.getElementById("busCodeEdit");
		
		cancelNewBus = document.getElementById("cancelNewBus");
		cancelNewBus.onclick = __this.hideAddBus;
		
		
		cancelEditBus = document.getElementById("cancelEditBus");
		cancelEditBus.onclick = __this.hideEditBus;
			
		submitNewBus = document.getElementById("submitNewBus");
		submitNewBus.onclick = __this.getSubmitNewBus;
		
		busCode = document.getElementById("busCode");
		
		submitEditBus=document.getElementById("submitEditBus");
		submitEditBus.onclick = __this.getSubmitEditBus;
	}
	
	this.checkBusControls = function(){
		
		if (busList.selectedIndex !=0 && busModelList.selectedIndex !=0) {
		
			editBus.disabled=false;
			delBus.disabled=false;
	   }
	   else {
			
			editBus.disabled=true;
			delBus.disabled=true;
	   }
	
	}
	
	this.getBusList=function(){
	
		//var busModelID = event.target.value;
		var busModelID = busModelList[busModelList.selectedIndex].value;
		
		if (busModelList.selectedIndex ==0) {
		
			addBus.disabled=true;
			busList.disabled=true;
			
			editBusModel.disabled=true;
			delBusModel.disabled=true;
			
		}
		else {
			
			//alert(busModelID);
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/buslist.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function(){__this.showBusList(ajaxCon);};
			ajaxCon.send("busModelID="+busModelID);
			
			editBus.disabled=true;
			delBus.disabled=true;
			
			addBus.disabled=false;
			busList.disabled=false;
			
			//addBusModel.disabled=false;
			editBusModel.disabled=false;
			delBusModel.disabled=false;
		
		}
	
	}
	
	this.showBusList = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			//alert('ajax ok');
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			if (json.result != "0"){
				
				busList.innerHTML=json.result;
				__this.loadEvents();
				
				}	
		}
	}
	
	this.showAddBus = function(){
	
		var ID = busModelList[busModelList.selectedIndex].value;
	
		if (ID!=0) {
			setFieldOff('busMain');
			setFieldOn('addBusField');
			busModelList.disabled = true;
			addBusModel.disabled = true;
			editBusModel.disabled = true;
			busCode.value="";
			
			//delBusModel.disabled = true;*/
		}
	}
	
	this.showEditBus = function(){
		
		var x = busList.selectedIndex;
		var ID = busList[x].value;
		var txt=busList[x].text;
		var used = busList[x].getAttribute('data-used');
	
		
		if (ID!=0) {
			
			if (used === 'true') {
		
				alert('Dieses Fahrzeug ist bereits in Betrieb. Aktive Fahrzeuge können nicht verändert werden.');
				return;
		
			}
			
			var selVal =  busList[x].text;
			busCodeEdit.value = selVal;
			
			setFieldOff('busMain');
			setFieldOn('editBusField');
			
			busModelList.disabled = true;
			addBusModel.disabled = true;
			editBusModel.disabled = true;
			//delBusModel.disabled = true;
		
		}
	}
	
	this.hideAddBus=function(){
		
		setFieldOff('addBusField');
		setFieldOn('busMain');
		busModelList.disabled = false;
		addBusModel.disabled = false;
		editBusModel.disabled = false;
		//delBusModel.disabled = false;
		
	}
	
	this.hideEditBus=function(){
		
		setFieldOff('editBusField');
		setFieldOn('busMain');
		busModelList.disabled = false;
		addBusModel.disabled = false;
		editBusModel.disabled = false;
	
	}
	
	this.getSubmitNewBus = function(){
		
		var y=busModelList.selectedIndex;
		var busModelID = busModelList[y].value;
		//cityText = cityList[y].text;
		var code = busCode.value;
		
		//alert(code);
		//alert(busModelID);
		//return;
		if (code.fulltrim()!='') {
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/submitnewbus.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function(){__this.showSubmitNewBus(ajaxCon);};
			
			ajaxCon.send("busModelID="+busModelID+"&code="+code);
		}
		
		else {
			
			alert('Bitte geben Sie ein valides Kennzeichen an.');
		}
		
	}
	
	this.showSubmitNewBus = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			__this.getBusList();
			__this.hideAddBus();
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
		}
	}
	
	this.getSubmitEditBus = function(){
		
		//alert("b");
		var x = busList.selectedIndex;
		var ID = busList[x].value;
		var selVal =  busList[x].text;
			
			//cityList = document.getElementById("");
			var y=busModelList.selectedIndex;
			var busModelID = busModelList[y].value;
			var busModelText = busModelList[y].text;
			
			var code = busCodeEdit.value;
			
			if (ID!=0 && busModelID !=0 && code.fulltrim()!=''){
				
				//var r=confirm("Wollen Sie den Bus ("+selVal+") sicher bearbeiten  ?" );
				
				//if (r==true) {
					
					//return;
					var ajaxCon = myAjax.connect();
					ajaxCon.open("POST","php/editbus.php",true);
					ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajaxCon.onreadystatechange = function(){
						__this.showSubmitEditBus(ajaxCon);
					};
				    
					ajaxCon.send("ID="+ID+"&busModelID="+busModelID+"&code="+code);
					//alert(myAjaxEditStation);
				
				//}
			}
			else {
			
				alert("Bitte geben Sie ein valides Kennzeichen an.");
			}
		
	}
	
	this.showSubmitEditBus = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
			
			//alert(ajaxCon.responseText);
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
		
			__this.getBusList();
			__this.hideEditBus();
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
			
		}
	
	}

}