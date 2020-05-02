<?php 

require ('db.php');

$searchArr['stationsIDs'] = trim($_POST['stationsIDs']);

$sql = 'SELECT count(ID) as counter FROM route WHERE stationsIDs=:stationsIDs';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$counter = $row['counter'];

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

if ($counter == 0) {
	
	////firstStationName
	$searchArr2['firstStationID'] = $_POST['firstStationID'];
	$sql = 'SELECT name FROM station WHERE ID=:firstStationID';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr2);
	$row = $PDOStatement->fetch();
	$firstStationName = $row['name'];
	
	////lastStationName
	$searchArr3['lastStationID'] = $_POST['lastStationID'];
	$sql = 'SELECT name FROM station WHERE ID=:lastStationID';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr3);
	$row = $PDOStatement->fetch();
	$lastStationName = $row['name'];
	
	$routeName = $firstStationName.' - '.$lastStationName;
	//echo $routeName;
	
	////////////////////////////////////
	
	$searchArr4['stationsIDs'] = $_POST['stationsIDs'];
	$searchArr4['numberOfStations'] = $_POST['numberOfStations'];
	$searchArr4['firstStationID'] = $_POST['firstStationID'];
	$searchArr4['lastStationID'] = $_POST['lastStationID'];
	$searchArr4['name'] = $routeName;
	
	
	$sql = '
	INSERT INTO route (numberOfStations,firstStationID,lastStationID,stationsIDs,name) 
	VALUES (:numberOfStations,:firstStationID,:lastStationID,:stationsIDs,:name)';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr4);	
	
	/////////////
	
	$searchArr5['stationsIDs'] = $_POST['stationsIDs'];
	$sql = 'SELECT ID FROM route WHERE stationsIDs=:stationsIDs';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr5);
	$row = $PDOStatement->fetch();
	$routeID = $row['ID'];
	
	//echo $ID;
	
	///////////////
	
	$numberOfStations = $_POST['numberOfStations'];
	$arrayStations = (explode(",",$_POST['stationsIDs']));
	
	for ($i=1;$i<=$numberOfStations;$i++) {
	
		$searchArr6['routeID'] = $routeID;
		$searchArr6['stationID'] = $arrayStations[$i-1];
		$searchArr6['stationOrder'] = $i;
		
		$sql = '
		INSERT INTO route_station_order ( routeID,stationID,stationOrder ) 
		VALUES (:routeID,:stationID,:stationOrder)';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr6);	
	}	
	echo 'Die Linie wurde erfolgreich gespeichert.';
}
else {	
		echo 'Diese Linie existiert bereits.';
}
?>