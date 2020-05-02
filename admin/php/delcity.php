<?php 


require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

$searchArr['ID'] = $_POST['ID'];

//check if city has stations 

$sql = 'SELECT * FROM city JOIN station ON city.ID = station.cityID WHERE city.ID = :ID';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$city = $PDOStatement->fetchAll();
$counter = count($city);

// end check if city has stations 

if ($counter == 0) {

	$sql = 'DELETE FROM city WHERE ID=:ID';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	///////////////////////////////SHOW RESULTS/////////////////////
	require ('citylist.sql.php');
	$ergebnis["message"]= "Stadt wurde erfolgreich gelöscht.";	

	}
	else {
	
	$ergebnis["message"] = "Eine Stadt mit (aktiven) Haltestellen kann nicht gelöscht werden.";
	$ergebnis["result"] = "0";
	
	}

echo json_encode($ergebnis);




?>
