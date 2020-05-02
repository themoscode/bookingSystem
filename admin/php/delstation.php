<?php 

require ('db.php');

$ergebnis = array(
"message" => "message",
"result" => "result"
);	


$sql = 'DELETE FROM station WHERE ID=:ID';

$searchArr['ID'] = $_POST['ID'];

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);	


///////////////////////////////SHOW RESULTS/////////////////////		
/*
$sql = 'SELECT ID, name FROM station 
	        WHERE cityID =:cityID 
			ORDER BY name ASC';
	
	$PDOStatement = $myPDO->prepare($sql);
	
	$searchArr2['cityID'] = $_POST['cityID'];
	$PDOStatement->execute($searchArr2);
	$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
	
	$stations = $PDOStatement->fetchAll();
	
	$ergebnis["result"] = "<option value='0'>Station......</option>";
	
	foreach ($stations as $index=>$station): 
	
		$ergebnis["result"] = $ergebnis["result"] . "<option value='" . $station['ID'] ."'>" . $station['name'] . "</option>";
	endforeach;
*/
////////////////////////////////////////////////
	
require ('stationlist.sql.php');

$ergebnis["message"]= "Haltestelle wurde erfolgreich gelöscht.";	

    //$ergebnis["message"]="cityID=".$_POST['cityID'].",ID=".$_POST['ID'];
	//$ergebnis["result"]="result";
	echo json_encode($ergebnis);
	//exit;
?>