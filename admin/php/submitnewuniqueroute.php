

<?php

require ('db.php');

////take the first bus you find in db
$searchArr['ID']=0;

$sql='SELECT

	  bus.ID as busID,
	  busmodel.numberOfSeats as freeSeats
	  
	  FROM bus
	  
	  JOIN busmodel ON bus.busModelID = busmodel.ID
	  
	  where bus.ID >:ID 
	  ORDER BY bus.ID LIMIT 1
	 ';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$busID = $row['busID'];
$freeSeats = $row['freeSeats'];

unset($searchArr);

///find the last inserted deparure date from unique_route and add 1 day

$searchArr['ID']=0;

$sql='SELECT ADDDATE( departure, 1 ) AS newDeparture
FROM unique_route
WHERE ID>:ID
ORDER BY departure DESC
LIMIT 1  ';
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$newDeparture = $row['newDeparture'];

echo "newDeparture=";
print_r ($row['newDeparture']);

$count = count($row['newDeparture']);

if ($count==0){
	$today = getdate();
	$newdate=date_create($today['year'].'-'.$today['mon'].'-'.$today['mday']);
	date_add($newdate,date_interval_create_from_date_string("1 days"));
	$newDeparture = date_format($newdate,"Y-m-d");
	
}

//////////////////////////////////////////

unset($searchArr);

$searchArr['routeID'] = $_POST['routeID'];
$searchArr['busID'] = $busID;

//$today = getdate();
//$searchArr['departure'] = $today['year'].'-'.$today['mon'].'-'.$today['mday'].' '.$today['hours'].':'.$today['minutes'].':'.$today['seconds'];
//$searchArr['arrival'] = $searchArr['departure'];

$searchArr['departure']=$newDeparture;
$searchArr['arrival'] = $newDeparture;

$searchArr['active']=0;

$sql='INSERT INTO unique_route (routeID,busID,departure,arrival,active) VALUES (:routeID,:busID,:departure,:arrival,:active)';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);	

//////////////get the last inserted unique_route idate

$searchArr2['routeID'] = $_POST['routeID'];
$sql = 'SELECT ID FROM unique_route WHERE routeID=:routeID ORDER BY ID DESC LIMIT 1';
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr2);
$row = $PDOStatement->fetch();
$uniqueRouteID = $row['ID'];

///////////////take the stationsIDs from route////////

$sql = 'SELECT stationsIDs FROM route WHERE ID=:routeID';
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr2);
$row = $PDOStatement->fetch();
$stationsIDs = $row['stationsIDs'];

//echo $stationsIDs;

$stationID = explode(",", $stationsIDs,-1);


//echo '<pre>';
//print_r($stationID);
//echo '</pre>';

//echo count($stationID);

for ($p=0;$p<count($stationID);$p++){

	for ($i=$p+1;$i<count($stationID);$i++){
		
		$searchArr3['routeID'] = $searchArr['routeID'];
		$searchArr3['uniqueRouteID'] = $uniqueRouteID; 
		$searchArr3['stationFromID'] = $stationID[$p];
		$searchArr3['stationToID'] = $stationID[$i];
		$searchArr3['price'] = 0;
		$searchArr3['departure'] = $searchArr['departure'];
		$searchArr3['arrival'] = $searchArr3['departure'];
		$searchArr3['reservedSeats'] = 0;
		$searchArr3['freeSeats'] = 0;
		
		//echo $stationID[$p].'--'.$stationID[$i].'<p>';
		$sql='INSERT INTO route_station_details (routeID,uniqueRouteID,stationFromID,stationToID,price,departure,arrival,reservedSeats,freeSeats) 
		      VALUES (:routeID,:uniqueRouteID,:stationFromID,:stationToID,:price,:departure,:arrival,:reservedSeats,:freeSeats)';
		
		$PDOStatement = $myPDO->prepare($sql);
        $PDOStatement->execute($searchArr3);			
	}
}

$result["uniqueRouteID"] = $uniqueRouteID;

echo json_encode($result);

?>