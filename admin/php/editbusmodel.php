<?php 

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

if (!is_numeric($_POST['numberOfSeats'])) {

	$ergebnis["message"] = "Bitte geben Sie eine Zahl ein.";
	$ergebnis["result"] = "0";
	echo json_encode($ergebnis);
	exit;

}


$searchArr['ID'] = $_POST['ID'];
$searchArr['model'] = $_POST['model'];
$searchArr['numberOfSeats'] = $_POST['numberOfSeats'];

//check if exists//

$sql = 'SELECT model,numberOfSeats FROM busmodel WHERE model=:model and numberOfSeats=:numberOfSeats and ID <> :ID';
		
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$busModel = $PDOStatement->fetchAll();
$counter = count($busModel);
//end check if exists

//echo "counter=".$counter;
   $ergebnis["message"] = "message test";
   $ergebnis["result"] = "counter=".$counter;
		
if ($counter == 0) {
	
	$sql = 'UPDATE busmodel SET model=:model, numberOfSeats=:numberOfSeats WHERE ID=:ID';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	//$ergebnis["message"] = "update ok"; 
	//$ergebnis["result"] = "update ok result"; 
	
	///////////////////////////////SHOW RESULTS/////////////////////		
	require ('busmodellist.sql.php');
	$ergebnis["message"]= "Das (Bus-)Modell wurde erfolgreich gespeichert.";	
	}
else {

	$ergebnis["message"] = "Dieses Modell existiert bereits.";
	$ergebnis["result"] = "0";
	}

echo json_encode($ergebnis);

?>