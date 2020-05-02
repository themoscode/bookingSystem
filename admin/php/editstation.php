<?php 

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);

$searchArr['ID'] = $_POST['ID'];
$searchArr['name'] = $_POST['name'];


//check if exists//

$sql = 'SELECT ID,name,cityID FROM station where name=:name and ID <>:ID';
		
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
$station = $PDOStatement->fetchAll();
$counter = count($station);

//$ergebnis["message"]="cityID=".$searchArr['cityID'].",ID=".$searchArr['ID'];
//$ergebnis["result"]="counter=".$counter;
//echo json_encode($ergebnis);
//exit;

//end check if exists

if ($counter == 0) {
	
	$sql = 'UPDATE station SET name=:name WHERE ID=:ID';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	
	///////////////////////////////SHOW RESULTS/////////////////////		
	require ('stationlist.sql.php');
	$ergebnis["message"]= "Die Haltestelle wurde erfolgreich aufgenommen.";	
	}
	
	else {

	$ergebnis["message"] = "Diese Haltestelle existiert bereits.";
	$ergebnis["result"] = "0";
	}

echo json_encode($ergebnis);

?>