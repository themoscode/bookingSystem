<?php 

require ('db.php');

$ergebnis = array(
"uniqueRouteExists" => false

);	

function dmy_To_ymd($str) {
	$dateArray = explode("-", $str); 
	$result = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
	return $result;
}

function getYear($str){
	$dateArray = explode("-", $str); 
	$result = $dateArray[2];
	return $result;
}

function getMonth($str){
	$dateArray = explode("-", $str); 
	$result = $dateArray[1];
	return $result;
}

function getDay($str){
	$dateArray = explode("-", $str); 
	$result = $dateArray[0];
	return $result;
}

function cancelBookingStatus($myPDO,$routeStationDetailsID){
	
		$arr['routeStationDetailsID']=$routeStationDetailsID;
		
		$sql = 'UPDATE booking SET bookStatus=3 WHERE routeStationDetailsID=:routeStationDetailsID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
	}

function cancelBookingStatus_forThisRide($myPDO,$data) {

		$arr['uniqueRouteID'] = $data['uniquerouteid'];
	
		$sql='SELECT ID from route_station_details WHERE uniqueRouteID=:uniqueRouteID';
	
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		foreach ($rows as $index=>$row):
		
			cancelBookingStatus($myPDO,$row['ID']);
		
		endforeach;


}	
	
	

//print_r ($_POST);



$searchArr['ID'] = $_POST['uniquerouteid'];
$searchArr['busID'] = $_POST['busid'];
//$searchArr['departure'] = dmy_To_ymd($_POST['departureDate']).' '.$_POST['departureTime'];
//$searchArr['arrival'] = dmy_To_ymd($_POST['arrivalDate']).' '.$_POST['arrivalTime'];

$searchArr['departureYear'] = getYear($_POST['departureDate']);
$searchArr['departureMonth'] = getMonth($_POST['departureDate']);
$searchArr['departureDay'] = getDay($_POST['departureDate']);


//$searchArr['arrivalYear'] = getYear($_POST['arrivalDate']);
//$searchArr['arrivalMonth'] = getMonth($_POST['arrivalDate']);
//$searchArr['arrivalDay'] = getDay($_POST['arrivalDate']);


$sql='SELECT count( ID ) AS count FROM unique_route 
	  where ID <> :ID 
	  
	  AND YEAR(departure)=:departureYear 
	  AND MONTH(departure)=:departureMonth 
	  AND DAY(departure)=:departureDay 
	  AND busID=:busID
	  AND active=1
	  
	 ';
																	
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$counter = $row['count'];

//echo $counter;

unset($searchArr);



if ($counter == 0) {
	
	/////////////take the seats of selected bus/////
	
		$searchArr['busID'] = $_POST['busid'];
		
		$sql='SELECT

		  busmodel.numberOfSeats as freeSeats
		  
		  FROM bus
		  
		  JOIN busmodel ON bus.busModelID = busmodel.ID
		  
		  where bus.ID =:busID 
		  ';
																		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		$freeSeats = $row['freeSeats'];
	
		// active=0 (not activated from admin)
		// active=1 (activated from admin)
		// active=-1 (cancelled from admin)
	
	if ($_POST['active'] == 0) { //not active or active2 editRouteStationDetailBut2
		
		unset($searchArr);
		
		$searchArr['ID'] = $_POST['uniquerouteid'];
		$searchArr['busID'] = $_POST['busid'];
		$searchArr['departure'] = dmy_To_ymd($_POST['departureDate']).' '.$_POST['departureTime'];
		$searchArr['arrival'] = dmy_To_ymd($_POST['arrivalDate']).' '.$_POST['arrivalTime'];
		
		$sql = 'UPDATE unique_route SET 
				busID=:busID,
				departure=:departure,
				arrival=:arrival
				WHERE ID=:ID';
	
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);	
		
		unset($searchArr);
		
		}
		
		else { //active or cancelled
				unset($searchArr);
				
				$searchArr['ID'] = $_POST['uniquerouteid'];
				$searchArr['active'] = $_POST['active'];
				$searchArr['busID'] = $_POST['busid'];
				
				$sql = 'UPDATE unique_route SET 
						active=:active,
						busID=:busID
						WHERE ID=:ID';
			
				$PDOStatement = $myPDO->prepare($sql);
				$PDOStatement->execute($searchArr);	
				
				unset($searchArr);
				
				
				if ($_POST['active'] == 1){$searchArr['freeSeats']=$freeSeats;}
				if ($_POST['active'] == -1){$searchArr['freeSeats']=0;}
				
				$searchArr['uniqueRouteID'] = $_POST['uniquerouteid'];
				
				$sql = 'UPDATE route_station_details SET 
						freeSeats=:freeSeats
						WHERE uniqueRouteID=:uniqueRouteID';
				
				$PDOStatement = $myPDO->prepare($sql);
				$PDOStatement->execute($searchArr);
				
				if ($_POST['active'] == -1) {
				
					cancelBookingStatus_forThisRide($myPDO,$_POST);
				
				}
				//cancel bookings//
				//cancel bookings//
		}
		
		$ergebnis["uniqueRouteExists"] = false;
}
else {

	$ergebnis["uniqueRouteExists"] = true;

}

echo json_encode($ergebnis);

?>
