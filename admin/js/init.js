
window.onload = function() {
	
	//**************start*************//
	
	$('#homeHeader').click(function(){window.location = 'index.php';})
	
	myAjax = new ajax();
	LB = new lightbox();
		
	myBusModel = new __busModel();
	myBusModel.getBusModelList();
	
	myBus = new __bus();
	
	myCity = new __city();
	myCity.getCityList();
	
	myStation = new __station();
	
	myRoute = new __route();
	myRoute.getRouteList();
	
	myTimeTable = new __timetable();
	myTimeTable.getTimeTableList();
	myTimeTable.loadEvents();
	
	myPassengerList = new __passengerList();
	myPassengerList.getSearchHeader();
	myPassengerList.loadEvents();
	
	myStats = new __stats();
	myStats.loadEvents();
	
	myBooking = new __booking();
	
	__JsDatePick = new Array();
	
	//**************start*************//
		
}