<?php

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

$searchArr['name'] = trim($_POST['name']);
$searchArr['cityID'] = $_POST['cityID'];

$sql = 'SELECT name FROM station WHERE name=:name AND cityID=:cityID';

$PDOStatement = $myPDO->prepare($sql);

$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$station = $PDOStatement->fetchAll();
$counter = count($station);


if ($counter == 0) {

	$sql = 'INSERT INTO station (cityID,name) VALUES (:cityID,:name)';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	$ergebnis["message"] = "Die Haltestelle wurde erfolgreich gespeichert.";
	
	
	////////////////////////result////////////////////
	
	require ('stationlist.sql.php');
	
	///////////////////end result/////////////////////////////////
	

}

else {
	
	$ergebnis["message"] = "Diese Haltestelle existiert bereits.";
	$ergebnis["result"] = "0";
}

echo json_encode($ergebnis);

?>