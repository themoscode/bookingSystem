<?php


include_once ("php/sessionconfig.php");
include_once ("php/sessioncontroller.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>FLOWERPOWER i BUS | Administration</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
		<link rel="stylesheet" href="css/lightbox.css">
	</head>
	
	<body>
		
		<header>
			<h1><a href="index.php">FLOWERPOWER i BUS</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Administration</h1>
			<div><?php echo $_SESSION["admin_user"]; ?></div>
			<a href="index.php?action=logout"><img src="images/logout.png" title="Logout"></a>
		</header>
		
		<div id="sideNavi">
			<nav>
				<ul>
					<li id="homeHeader">START</li>
				  	<li id="busHeader">MODELLE & FAHRZEUGE</li>
				  	<li id="cityHeader">STÄDTE & HALTESTELLEN</li>
				  	<li id="routeHeader">LINIEN & FAHRTEN</li>
				  	<li id="timeTableHeader">FAHRPLÄNE</li>
				  	<li id="bookingTableHeader" class="fieldOff">BUCHUNGEN</li>
				  	<li id="passengerListHeader">PASSAGIERLISTEN</li>
				  	<li id="statsHeader">STATISTIKEN</li>
				</ul>
			</nav>
		</div>            
		
		<div id="homePage">
			<div id="worldclock" class="">
				<iframe scrolling="no" frameborder="no" 
				src="http://www.clocklink.com/clocks/HTML5/html5-world.html?Berlin&Frankfurt&New_York&720&gray"></iframe>
			</div>
		</div>
		
		<fieldset id="mainCMS">
			 
			<div id="busModel" class="fieldOff">
				<h3>MODELLE & FAHRZEUGE</h3>
				<fieldset id="busModelMain" class="fieldOn">
					
					<legend>Modelle</legend>
					<select id="busModelList" class="listBox">
						<option>(Bus-)Modell...</option>
					</select>
					
					<div id="busModelMainButtons" >
						<input type="button" value="Neues (Bus-)Modell" id="addBusModel" >
						<input type="button" value="Modell bearbeiten" id="editBusModel" disabled>
						<input type="button" value="Modell löschen" id="delBusModel" disabled class="fieldOff">
					</div>
				</fieldset> 
				
				<fieldset id="addBusModelField" class="fieldOff">
					<legend>Neues (Bus-)Modell</legend>
					<input type="text" placeholder="(Bus-)Modell..." id="model" name="model" >
					<input type="text" placeholder="Platzanzahl..." id="numberOfSeats" name="numberOfSeats" >
					<input type="button" value="Neues (Bus-)Modell speichern" id="submitNewBusModel">
					<input type="button" value="<< zurück" id="cancelNewBusModel">
				</fieldset>
				
				<fieldset id="editBusModelField" class="fieldOff">
					<legend>(Bus-)Modell bearbeiten</legend>
					<input type="text" placeholder="(Bus-)Modell..." id="modelEdit" name="model" >
					<input type="text" placeholder="Platzanzahl..." id="numberOfSeatsEdit" name="numberOfSeats" >
					<input type="button" value="(Bus-)Modell speichern" id="submitEditBusModel">
					<input type="button" value="<< zurück" id="cancelEditBusModel">
				</fieldset>
				
				<div id="bus">
					<fieldset id="busMain" class="fieldOn">
						<legend>Fahrzeuge des ausgewählten Modells</legend>
						<select id="busList" class="ListBox" disabled>
							<option value="0">Kennzeichen...</option>
						</select>
						<div id="busMainButtons" >
							<input type="button" value="Neues Fahrzeug anlegen" id="addBus" >
							<input type="button" value="Fahrzeug bearbeiten" id="editBus" disabled>
							<input type="button" value="Fahrzeug löschen" id="delBus" disabled class="fieldOff">
						</div>
					</fieldset>
					<fieldset id="addBusField" class="fieldOff">
						<legend>Neues Fahrzeug</legend>
						<input type="text" placeholder="Neues Kennzeichen..." id="busCode" name="busCode" >
						<input type="button" value="Neues Fahrzeug speichern" id="submitNewBus">
						<input type="button" value="<< zurück" id="cancelNewBus">
					</fieldset>
					<fieldset id="editBusField" class="fieldOff">
						<legend>Fahrzeug bearbeiten</legend>
						<input type="text" placeholder="Kennzeichen..." id="busCodeEdit" name="busCode" >
						<input type="button" value="Fahrzeug speichern" id="submitEditBus">
						<input type="button" value="<< zurück" id="cancelEditBus">
					</fieldset>
				</div>
				
			</div>
			
			<div id="city" class="fieldOff">
				<h3>STÄDTE & HALTESTELLEN</h3>
				<fieldset id="cityMain" class="fieldOn">
					<legend>Städte</legend>
					<select id="cityList" class="ListBox">
						<option value="0">Bitte auswählen...</option>
					</select>
					<div id="cityMainButtons" >
						<input type="button" value="Neue Stadt..." id="addCity">
						<input type="button" value="Stadt bearbeiten" id="editCity" disabled>
						<input type="button" value="Stadt löschen" id="delCity" disabled class="fieldOff">
					</div>
				</fieldset>
				<fieldset id="addCityField" class="fieldOff">
					<legend>Neue Stadt</legend>
					<input type="text" placeholder="Neue Stadt..." id="cityName" name="cityName" >
					<input type="button" value="Neue Stadt speichern" id="submitNewCity">
					<input type="button" value="<< zurück" id="cancelNewCity">
				</fieldset>
				<fieldset id="editCityField" class="fieldOff">
					<legend>Stadt bearbeiten</legend>
					<input type="text" placeholder="Stadt..." id="cityNameEdit" name="cityNameEdit" >
					<input type="button" value="Stadt speichern" id="submitEditCity">
					<input type="button" value="<< zurück" id="cancelEditCity">
				</fieldset>
				<div id="station" >
					<fieldset id="stationMain" class="fieldOn">
						<legend>Haltestellen der gewählten Stadt</legend>
						<select id="stationList" class="ListBox" disabled>
							<option value="0">Haltestelle...</option>
						</select>
						<div id="stationMainButtons" >
							<input type="button" value="Neue Haltestelle" id="addStation" disabled>
							<input type="button" value="Haltestelle bearbeiten" id="editStation" disabled>
							<input type="button" value="Haltestelle löschen" id="delStation" disabled class="fieldOff">
						</div>
					</fieldset>
					<fieldset id="addStationField" class="fieldOff">
						<legend>Neue Haltestelle in gewählter Stadt</legend>
						<input type="text" placeholder="Neue Haltestelle..." id="stationName" name="stationName" >
						<input type="button" value="Neue Haltestelle speichern" id="submitNewStation">
						<input type="button" value="<< zurück" id="cancelNewStation">
					</fieldset>
					<fieldset id="editStationField" class="fieldOff">
						<legend>Haltestelle bearbeiten</legend>
						<input type="text" value="Haltestelle..." id="stationNameEdit" name="stationNameEdit" >
						<input type="button" value="Haltestelle speichern" id="submitEditStation">
						<input type="button" value="<< zurück" id="cancelEditStation">
					</fieldset>
				</div>
			</div>
			
			<div id="route" class="fieldOff"></div>
			
			<div id="timeTable" class="fieldOff"></div>
			
			<div id="bookingTable" class="fieldOff"></div>
			
			<div id="passengerList" class="fieldOff"></div>
			
			<div id="stats" class="fieldOff">
				<h3>STATISTIKEN</h3>
				<div id="webalizerHeader">WEBALIZER</div>
				<div id="webalizer" class="fieldOff"><iframe src="http://localhost/myweb/flowerpower/usage/"></iframe></div>	
				<div id="clickCounterHeader">CLICK COUNTER</div>
				<div id="clickCounter" class="fieldOff"><iframe src="http://localhost/myweb/flowerpower/admin/click_counter/"></iframe></div>
			</div>
			
		</fieldset>
		
		
		<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="js/class.ajax.js" ></script>
		<script type="text/javascript" src="js/class.busModel.js" ></script>
		<script type="text/javascript" src="js/class.bus.js" ></script>
		<script type="text/javascript" src="js/class.city.js" ></script>
		<script type="text/javascript" src="js/class.station.js" ></script>
		<script type="text/javascript" src="js/class.route.js" ></script>
		<script type="text/javascript" src="js/class.timetable.js" ></script>
		<script type="text/javascript" src="js/class.passengerList.js" ></script>
		<script type="text/javascript" src="js/class.stats.js" ></script>
		<script type="text/javascript" src="js/class.booking.js" ></script>
		<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
		<script type="text/javascript" src="js/class.lightbox.js" ></script>
		<script type="text/javascript" src="js/functions.js" ></script>
		<script type="text/javascript" src="js/init.js" ></script>
		
	</body>
</html>