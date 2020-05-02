function __busModel(){
	
	var __this = this;
	
	this.loadEvents = function (){
			//bus Model
		
		/////////////////////////////////////////////////////////////
		
		busHeader = document.getElementById("busHeader");
		busHeader.onclick = function() {setField('busModel'); $('#homePage').hide();};
		
		//busModelMain = document.getElementById("busModelMain");
		
		busModelList = document.getElementById("busModelList");
			busModelList.onchange =  function(){
				myBus.getBusList();
				myBus.checkBusControls();
			};  
		
		//busModelMainButtons = document.getElementById("busModelMainButtons");
		
		addBusModel = document.getElementById("addBusModel");
		addBusModel.onclick = __this.showAddBusModel;
		
		editBusModel = document.getElementById("editBusModel");
		editBusModel.onclick = __this.showEditBusModel;
		
		delBusModel = document.getElementById("delBusModel");
		delBusModel.onclick = __this.getDelBusModel;
			
		//addBusModelField = document.getElementById("addBusModelField");
		
		model = document.getElementById("model");
		numberOfSeats = document.getElementById("numberOfSeats");
		
		submitNewBusModel = document.getElementById("submitNewBusModel");
		submitNewBusModel.onclick = __this.getSubmitNewBusModel;
		
		cancelNewBusModel = document.getElementById("cancelNewBusModel");
		cancelNewBusModel.onclick = __this.hideAddBusModel;
			
		submitEditBusModel=document.getElementById("submitEditBusModel");
		submitEditBusModel.onclick = __this.getSubmitEditBusModel;	
		
		cancelEditBusModel = document.getElementById("cancelEditBusModel");
		cancelEditBusModel.onclick = __this.hideEditBusModel;
		
		modelEdit = document.getElementById("modelEdit");
		numberOfSeatsEdit = document.getElementById("numberOfSeatsEdit");
			
		//bus Model end
	}
	
	this.check_if_used = function (msg,whichID){
	
	
	
	}
	
	
	this.showEditBusModel = function(){
		
		//alert('');
		
		var x = busModelList.selectedIndex;
		var ID = busModelList[x].value;
		var used = busModelList[x].getAttribute('data-used');
		
		if (ID!=0) {
		
			if (used === 'true') {
			
				alert('Dieses Modell ist bereits in Betrieb. Aktive Modelle können nicht verändert werden.');
				return;
			
			}
			
		selVal =  busModelList[x].text;
		res = selVal.split(", Plätze:");
		
		numberOfSeatsEdit.value = res[res.length-1];
		modelEdit.value = res[0];
		setFieldOff('busModelMain');
		setFieldOn('editBusModelField');
		
		}
	
	}

	this.hideEditBusModel = function(){
	
		setFieldOff('editBusModelField');
		setFieldOn('busModelMain');
	
	}
	
	this.getSubmitEditBusModel = function(){
		
			x = busModelList.selectedIndex;
			ID = busModelList[x].value;
			selVal =  busModelList[x].text;
			
			modelVal=modelEdit.value;
			
			modelVal=modelVal.fulltrim();
			numberOfSeatsVal=numberOfSeatsEdit.value;
			
			//alert(modelVal);
			//alert(numberOfSeatsVal);
			
			if (ID!=0 && modelVal!='' && numberOfSeatsVal!=0){
				
				//var r=confirm("Wollen Sie den Bus ("+selVal+") sicher bearbeiten?" );
				
				//if (r==true) {
				    var ajaxCon = myAjax.connect();
					ajaxCon.open("POST","php/editbusmodel.php",true);
					ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajaxCon.onreadystatechange = function(){
						__this.showSubmitEditBusModel(ajaxCon);
					};
				
					ajaxCon.send("ID="+ID+"&model="+modelVal+"&numberOfSeats="+numberOfSeatsVal);
				
				//}	
			}
			else {		
					alert("Bitte geben Sie ein Modell mit entsprechender Platzzahl an.");			
				 }
	}
	
	this.showSubmitEditBusModel = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				
				//busModelList.innerHTML=json.result;
				__this.getBusModelList();
				busModelList.selectedIndex=0;
				myBus.getBusList();
				__this.hideEditBusModel(); 
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				
			}
			
		}
	
	}
	
	this.showDelBusModel=function(ajaxCon){
	
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
				
				__this.getBusModelList();
				busModelList.selectedIndex=0;
				myBus.getBusList();
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
			}
			
		}
	
	}
	
	
	this.getDelBusModel = function(){
		
		x = busModelList.selectedIndex;
		ID = busModelList[x].value;
		selVal =  busModelList[x].text;
		
		if (ID!=0){
		
			var r=confirm("Wollen Sie das (Bus-)Modell ("+selVal+") wirklich löschen?" );
			
			if (r==true) {
				var ajaxCon = myAjax.connect();
				ajaxCon.open("POST","php/delbusmodel.php",true);
				ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajaxCon.onreadystatechange = function(){
					__this.showDelBusModel(ajaxCon);
				};
				
				ajaxCon.send("ID="+ID);
			}
		}
	}
	
	this.getSubmitNewBusModel = function(){
		
		modelVal=model.value;
		modelVal=modelVal.fulltrim();
		
		numberOfSeatsVal=numberOfSeats.value;
		
		if ( modelVal !="" && numberOfSeatsVal!=0) {
			
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/submitnewbusmodel.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function(){
				__this.showSubmitNewBusModel(ajaxCon);
			};
		
			ajaxCon.send("model="+modelVal+"&numberOfSeats="+numberOfSeatsVal);
		
		}
		else {
			alert("Bitte geben Sie ein (Bus-)Modell mit entsprechender Platzzahl an.");
		}
	}
	
	this.showSubmitNewBusModel = function(ajaxCon) {
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			alert(json.message);
			
			if (json.result != "0"){
			
				//busModelList.innerHTML=json.result;
				__this.getBusModelList();
				busModelList.selectedIndex=0;
				//
				__this.hideAddBusModel(); 
				myBus.getBusList();
				myBus.checkBusControls();
				
				////
				myRoute.getRouteList();
				myTimeTable.getTimeTableList();
				myBooking.hide();
				myPassengerList.initResults();
				////
				}
			
		}
	}
	
	this.showAddBusModel=function(){
		
		setFieldOff('busModelMain');
		setFieldOn('addBusModelField');
		addBus.disabled=true;
		editBus.disabled=true;
		delBus.disabled=true;
		busList.disabled=true;
		model.value="";
		numberOfSeats.value="";
	}
	
	this.hideAddBusModel = function(){
		
		setFieldOff('addBusModelField');
		setFieldOn('busModelMain');
		addBus.disabled=false;
		editBus.disabled=false;
		delBus.disabled=false;
		busList.disabled=false;
	}
	
	this.getBusModelList=function(){
	
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/busmodellist.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){
			__this.showBusModelList(ajaxCon);
		};
		ajaxCon.send();
	}
	
	this.showBusModelList=function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			//alert(myAjax.responseText);
			//testArea.innerHTML = myAjaxBus.responseText; 
			//busList.innerHTML = myAjaxBus.responseText; 
			//testArea.innerHTML = myAjaxBus.responseText; 
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			if (json.result != "0"){
				busModelList.innerHTML=json.result;
				__this.loadEvents();
				busList.disabled = true;
				addBus.disabled = true;
				editBus.disabled = true;	
			}	
		}
	}
}	