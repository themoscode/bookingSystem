<?php

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);

$searchArr['code'] = $_POST['code'];
$searchArr['ID'] = $_POST['ID'];

$sql = 'SELECT count(ID) as counter FROM bus WHERE code=:code AND ID<>:ID';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$counter = $row['counter'];


if ($counter == 0) {

	
	$sql = 'UPDATE bus SET code=:code WHERE ID=:ID';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	
	///////////////////////////////SHOW RESULTS/////////////////////		
	//require ('stationlist.sql.php');
	$ergebnis["message"]= "Das Fahrzeug wurde erfolgreich gespeichert.";	
	$ergebnis["result"] = "1";
	}
	
	else {

	$ergebnis["message"] = "Ein Fahrzeug mit diesem Kennzeichen existiert bereits.";
	$ergebnis["result"] = "0";
	}

echo json_encode($ergebnis);

?>
