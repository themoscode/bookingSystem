<?php 

//print_r ($_POST);


function dmy_To_ymd($str) {
	
	$dateArray = split ("\-", $str); 

	$result = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];

	return $result;
}



require ('db.php');

$searchArr['routestationdetailid'] = $_POST['routestationdetailid'];
$searchArr['price'] = $_POST['price'];
$searchArr['departure'] = dmy_To_ymd($_POST['departureDate']).' '.$_POST['departureTime'];
$searchArr['arrival'] = dmy_To_ymd($_POST['arrivalDate']).' '.$_POST['arrivalTime'];


$sql = 'UPDATE route_station_details 
SET price=:price,
departure=:departure,
arrival=:arrival
WHERE ID=:routestationdetailid';
	

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);	

?>