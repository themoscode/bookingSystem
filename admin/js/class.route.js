
function __route(){

	var __this = this;
	
	this.loadEvents = function(){
		
	
		routeHeader = document.getElementById("routeHeader");
		routeHeader.onclick = function() {setField('route');$('#homePage').hide();};
		
		//setTimeout(function(){showDatePickerForClassName('dateField');},10000);
		//setTimeout(function(){showDatePickerForClassName('dateField_search');},10000);
		
		showDatePickerForClassName('dateField');
		showDatePickerForClassName('dateField_search');
		
		setFieldForClassName('routeDetails','routeStationDetails');
		setFieldForClassName('butRouteStationOrder','routeStationOrder');
				
		__this.editRouteStationDetailBut('editRouteStationDetailBut');
		__this.editRouteStationDetailBut('editRouteStationDetailBut2');
		__this.editRouteStationDetailBut('editRouteStationDetailBut_online');
		__this.editRouteStationDetailBut('editRouteStationDetailBut_cancel');
		
		setFieldForIdandTargetId('butNewRoute','newRoute');
		__this.showListStations('selectNumberOfStations','tdSelectStations');
		__this.checkIfValidNewRoute();
		__this.checkHeaders();
		
	
	}
	
	
	this.check_price = function(obj){
		
		var val = obj.value;
	
		var intRegex = /^\d+$/;
		if(intRegex.test(val) && val>0) {
		   
		   return true;
		   }
		
		 obj.focus();  
		 return false;
	}
	
	
	this.getRouteList = function(){
		
		var _arguments = arguments;
		
		var searchDateFrom = take_date_today();
		var searchDateTo = take_date_today('+1 year later');
		
		if (arguments.length>0) { 
			
			if ($('#searchDateFrom_'+arguments[0]).length > 0){
				
				var searchDateFrom_obj = document.getElementById('searchDateFrom_'+arguments[0]);
				var searchDateFrom = searchDateFrom_obj.value;
				
				var searchDateTo_obj = document.getElementById('searchDateTo_'+arguments[0]);
				var searchDateTo = searchDateTo_obj.value;
				
				
				if (isValidDate(searchDateFrom_obj) === false || isValidDate(searchDateTo_obj) === false) {
			
					alert("Bitte geben Sie ein gültiges Zeitformat an [dd-mm-yyyy]."); 
					return;
				
				}
				
				
			}
			
		}
		
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/routelist.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){
						
			if (_arguments.length <= 1) {
				__this.showRouteList(ajaxCon);
			}
			
			else if (_arguments.length > 1) {
				
				__this.showRouteList(ajaxCon,_arguments[1]);
			
			}
		
		
		
		};
		
		if (arguments.length>0) {ajaxCon.send("searchDateFrom="+searchDateFrom+"&searchDateTo="+searchDateTo+"&routeID="+arguments[0]);}
		else {ajaxCon.send();}
		}
		
	
	this.checkHeaders = function(){
		
		$( ".ridesMainTable .PastRide" ).prev().addClass("PastRide"); //make the headers opacity:0.5 when the Ride is Past
		
		$('.von_nach_header').each(function() {
					if ($(this).next('tr').length === 0) {
						$(this).hide();
					}
				});
	
	}
	
	
	
	this.showRouteList = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			//route.innerHTML = ajaxCon.responseText;
				
				//alert(json.result);
				route.innerHTML=json.result;
				//console.log(json.result);
				__this.loadEvents();
				
				
				
				//alert(arguments.length);
				
				if (arguments.length > 1) {
					
					$('.uniqueRoute_'+arguments[1]).css("background-color","#B5E5A9"); 
					$('.uniqueRoute_'+arguments[1]).find('input[type=text]').val('');
					$('.uniqueRoute_'+arguments[1]).find('input[type=text]').filter(':first').focus();
					
				}
		}
	}
	
	this.checkIfValidNewRoute = function(){
		
		butSaveNewRoute = document.getElementById('butSaveNewRoute');
		butSaveNewRoute.onclick=function(){

			selectNumberOfStations = document.getElementById('selectNumberOfStations');
			
			var numberOfStations = selectNumberOfStations.value;
			
			var stationsIDs = '';
			var currentStationListBox = '';
			
			var firstStationID = document.getElementById('StationListBox_1').value;
			var lastStationID = document.getElementById('StationListBox_'+numberOfStations).value;
			
			for (var i=1;i<=numberOfStations;i++) {
				
				currentStationListBoxValue = document.getElementById('StationListBox_'+i).value;
				var tmp=','+stationsIDs;
				if (tmp.indexOf(','+currentStationListBoxValue+',') > -1) {
					alert('Haltestellen dürfen sich nicht wiederholen.');
					return;
				}
				
				stationsIDs = stationsIDs + currentStationListBoxValue + ',';
			}
			
			__this.getInsertNewRoute(stationsIDs,numberOfStations,firstStationID,lastStationID);

		}
		
	}
	
	this.getInsertNewRoute = function(stationsIDs,numberOfStations,firstStationID,lastStationID){
	
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/submitnewroute.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){__this.showInsertNewRoute(ajaxCon);};
		ajaxCon.send("stationsIDs="+stationsIDs+"&numberOfStations="+numberOfStations+"&firstStationID="+firstStationID+"&lastStationID="+lastStationID);

	}
	
	this.showInsertNewRoute = function(ajaxCon){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
		
			alert(ajaxCon.responseText);
			__this.getRouteList();
			
			////
			myTimeTable.getTimeTableList();
			myBusModel.getBusModelList();
			myCity.getCityList();
		
			////
			}
	
	}
	
	this.showListStations = function(fromID,target){
		
		//show list boxes with the stations
		var G=document.getElementById(fromID);	

		G.onchange = function(){
			
			//
			var butSaveNewRoute = document.getElementById('butSaveNewRoute');
			if (G.value == 0) {
				butSaveNewRoute.disabled = true;
				//setFieldOff('newRouteStationDetail');
				}
			else {
				butSaveNewRoute.disabled = false;
				//setFieldOn('newRouteStationDetail');
				}
				
			var selNumberOfStations = this.value;
			var numberOfStations = this.getAttribute('data-numberofstations');
			
			
			//on - off list boxes
			
			for (var j=1;j<=numberOfStations;j++) {
				//alert(j);
				setFieldOff('StationList_'+j);
				
			}
			
			for (var j=1;j<=selNumberOfStations;j++) {
				
				setFieldOn('StationList_'+j);
				var StationList = document.getElementById('StationList_'+j);
				
			}
			
			setFieldOn(target);
			
		};
	 
	}
	
	this.showUpdateRouteStationDetail = function(ajaxCon,routestationdetailid){
		
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {}
		
	}
	
	this.getUpdateRouteStationDetail = function(routeid,routestationdetailid){
		
		var index = routeid+routestationdetailid;
		var price = document.getElementById('price_'+routeid+'_'+routestationdetailid);
		var departureDate = document.getElementById('departureDate_'+routeid+'_'+routestationdetailid);
		var departureTime = document.getElementById('departureTime_'+routeid+'_'+routestationdetailid);
		var arrivalDate = document.getElementById('arrivalDate_'+routeid+'_'+routestationdetailid);
		var arrivalTime = document.getElementById('arrivalTime_'+routeid+'_'+routestationdetailid);
		
		
		
			price = price.value;
			departureDate = departureDate.value;
			departureTime = departureTime.value;
			arrivalDate = arrivalDate.value;
			arrivalTime = arrivalTime.value;
			
			//alert(myAjaxRouteStationDetail[index]);
			var ajaxCon = myAjax.connect();
			ajaxCon.open("POST","php/routestationdetail.php",true);
			ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajaxCon.onreadystatechange = function (){
			
			//showUpdateRouteStationDetail();
			__this.showUpdateRouteStationDetail(ajaxCon,routestationdetailid);
			
			};
			
			
			ajaxCon.send("routestationdetailid="+routestationdetailid+
			"&price="+price+
			"&departureDate="+departureDate+
			"&departureTime="+departureTime+
			"&arrivalDate="+arrivalDate+
			"&arrivalTime="+arrivalTime);
			
			return true;
		
		
	}
	
	this.showEditUniqueRoute = function(routeid,uniquerouteid,routestationdetailsids,busid,ajaxCon){
		
			if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
			
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			if (json.uniqueRouteExists === false) {
				__this.checkEditSetOfRouteStationDetails(routeid,uniquerouteid,routestationdetailsids,busid);
			
			}
			else {
				alert("Diese Fahrtangaben sind ungültig. Bitte überprüfen Sie Ihre Eingabe.");
			}

		}

	}
	
	
	function unique_route_data_validation(routeid,uniquerouteid,routestationdetailsids,busid,active) {
	
	
		var res = routestationdetailsids.split(','); 
		var lngth = parseInt((res.length)-2,10);
		
		var uniqiue_route_departure = res[0];
		var uniqiue_route_arrival = res[lngth];
		
		var today_full = today_ymd_hms_ms();
		
		//alert(today_full);
		
		for (var i=0;i<=lngth;i++) {
			
			var departureDate_obj = document.getElementById('departureDate_'+routeid+'_'+res[i]);
			
			var departureDate = departureDate_obj.value;
			departureDate = departureDate.split("-").reverse().join("-");
			
			var departureTime_obj = document.getElementById('departureTime_'+routeid+'_'+res[i]);
			var departureTime = departureTime_obj.value;
			
			var departure = new Date(departureDate+'T'+departureTime+':00');
			departure = departure.getTime();
			departure = parseInt(departure,10);
			////////
			
			var arrivalDate_obj = document.getElementById('arrivalDate_'+routeid+'_'+res[i]);
			
			var arrivalDate = arrivalDate_obj.value;
			arrivalDate = arrivalDate.split("-").reverse().join("-");
			
			var arrivalTime_obj = document.getElementById('arrivalTime_'+routeid+'_'+res[i]);
			var arrivalTime = arrivalTime_obj.value;
			
			var arrival = new Date(arrivalDate+'T'+arrivalTime+':00');
			arrival = arrival.getTime();
			arrival = parseInt(arrival,10);
			
			//console.log(departure);
			//console.log(arrival);
			
			var price_obj = document.getElementById('price_'+routeid+'_'+res[i]);
			
			if (isValidDate(departureDate_obj) === false || isValidDate(arrivalDate_obj) === false) {
			
				alert("Bitte geben Sie ein gültiges Zeitformat an [dd-mm-yyyy]."); 
				return false;
			
			}
			
			if (checkTimeFormat(departureTime_obj) === false || checkTimeFormat(arrivalTime_obj) === false){
				alert("Bitte geben Sie ein gültiges Zeitformat an:[mm:ss]"); 
				return false;
			}
			
			if( arrival <= departure ){// || departure < today_full
			
				alert("Diese Fahrtangaben sind ungültig. Bitte überprüfen Sie Ihre Eingabe.");
				departureDate_obj.focus();
				return false;
			}
			
			if (__this.check_price(price_obj) === false){
				 alert("Der Preis muss ein numerischer Wert sein.");
				 return false;
			}
			
		}
		
		return true;
	
	}
	
	
	this.editUniqueRoute = function(routeid,uniquerouteid,routestationdetailsids,busid,active){
		
		//alert(active);
		var res = routestationdetailsids.split(','); 
		var lngth = parseInt((res.length)-2,10);
		
		var uniqiue_route_departure = res[0];
		var uniqiue_route_arrival = res[lngth];
		
		if (unique_route_data_validation(routeid,uniquerouteid,routestationdetailsids,busid,active) === false) {
			return;
		}
		
		var departureDate = document.getElementById('departureDate_'+routeid+'_'+uniqiue_route_departure).value;
		var departureTime = document.getElementById('departureTime_'+routeid+'_'+uniqiue_route_departure).value;
		var arrivalDate = document.getElementById('arrivalDate_'+routeid+'_'+uniqiue_route_arrival).value;
		var arrivalTime = document.getElementById('arrivalTime_'+routeid+'_'+uniqiue_route_arrival).value;
		
		//alert(uniquerouteid);
		var ajaxCon = myAjax.connect();
		ajaxCon = new XMLHttpRequest();
		
		//alert(myAjaxEditUniqueRoute[uniquerouteid]);
		
		ajaxCon.open("POST","php/edituniqueroute.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function (){
			
			__this.showEditUniqueRoute(routeid,uniquerouteid,routestationdetailsids,busid,ajaxCon);
			
		};
		ajaxCon.send("uniquerouteid="+uniquerouteid+
				"&busid="+busid+
				"&departureDate="+departureDate+
				"&departureTime="+departureTime+
				"&arrivalDate="+arrivalDate+
				"&arrivalTime="+arrivalTime+
				"&active="+active
				);
		
		//alert(res[lngth]);
			
		}
	
	this.checkEditSetOfRouteStationDetails = function(routeid,uniquerouteid,routestationdetailsids,busid){
	
		var res = routestationdetailsids.split(','); 
		var lngth = parseInt((res.length)-2,10);
		for (var i=0;i<=lngth;i++) {
			if (__this.getUpdateRouteStationDetail(routeid,res[i])=== false) {return;}
		}
		alert('Die Fahrt wurde erfolgreich aktualisiert.');
		
		myBusModel.getBusModelList();
		myCity.getCityList();
		myRoute.getRouteList(routeid);
		myTimeTable.getTimeTableList();
		myPassengerList.initResults();
		myBooking.hide();
	}
	
	this.editRouteStationDetailBut = function(className){
	
		var G=document.getElementsByClassName(className);
		var active=0;
		
		if (className === 'editRouteStationDetailBut') {active=0;}
		if (className === 'editRouteStationDetailBut_online') {active=1;}
		if (className === 'editRouteStationDetailBut_cancel') {active=-1;}
	
		 for (var i=0; i<G.length; i++) {
			
			G[i].onclick = function(){
				
				var routeid = this.getAttribute('data-routeid');
				var uniquerouteid = this.getAttribute('data-uniquerouteid');
				var routestationdetailsids = this.getAttribute('data-routestationdetailsids');
				var busid = document.getElementById('busID_'+uniquerouteid).value;
				
				if (className === 'editRouteStationDetailBut_cancel') {
					
					var bookingsNum = this.getAttribute('data-bookings');
					if (parseInt(bookingsNum,10)>0) {
						var msg = confirm("Für diese Fahrt gibt es bereits "+bookingsNum+" Buchung(en). Sind Sie sicher, dass Sie die Fahrt stornieren wollen? - Bitte benachrichtigen Sie jetzt Ihre Passagiere!");
						if (msg === false) {return;}
					}
					else {
						var msg = confirm("Sind Sie sicher, dass Sie die Fahrt stornieren wollen?");
						if (msg === false) {return;}
					}
					
				}
				
				__this.editUniqueRoute(routeid,uniquerouteid,routestationdetailsids,busid,active)
				
			};
		 
		 }

	}
	
	this.showNewUniqueRoute = function(routeID,ajaxCon){
			
		if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
			
			console.log("responseText",ajaxCon.responseText);
			
			var json = eval( "(" + ajaxCon.responseText  + ")" );
			
			//alert(json.uniqueRouteID);
			//return;
			////
			__this.getRouteList(''+routeID,json.uniqueRouteID);
			
			myBusModel.getBusModelList();
			myCity.getCityList();
			myTimeTable.getTimeTableList();
			
		    ////
			
			}
	
	}
	
	this.newUniqueRoute = function(routeID){
	
		var ajaxCon = myAjax.connect();
		
		ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/submitnewuniqueroute.php",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function (){
			
			__this.showNewUniqueRoute(routeID,ajaxCon);
		};
		ajaxCon.send("routeID="+routeID);
	}
	
	this.updateFreeSeats = function(){
		
		var seats=obj[obj.selectedIndex].getAttribute('data-seats');
		//alert(seats);
		//alert(uniqueRouteID);
		
		var tdseats = getElementsByAttribute('data-tdseatsid');
		
		for (var i=0;i<tdseats.length;i++) {
			
			if (parseInt(tdseats[i].getAttribute('data-tdseatsid'),10)===uniqueRouteID) {
				tdseats[i].innerHTML = seats;
			}
			
		}
		
	}

}
