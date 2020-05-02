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

//check if exists///
$searchArr['model'] = trim($_POST['model']);
$searchArr['numberOfSeats'] = $_POST['numberOfSeats'];

$sql = 'SELECT model,numberOfSeats FROM busmodel WHERE model=:model and numberOfSeats=:numberOfSeats';
		
$PDOStatement = $myPDO->prepare($sql);

$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$busModel = $PDOStatement->fetchAll();
$counter = count($busModel);

//echo " counter=".$counter;


if ($counter == 0) {
		
	$sql = 'INSERT INTO busmodel (model,numberOfSeats) VALUES (:model,:numberOfSeats)';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	$ergebnis["message"] = "Das (Bus-)Modell wurde erfolgreich gespeichert.";
	
	
	////////////////////////result////////////////////
	
	require ('busmodellist.sql.php');
	
	///////////////////end result/////////////////////////////////		
}
else {

	$ergebnis["message"] = "Das Modell existiert bereits.";
	$ergebnis["result"] = "0";
}
		
echo json_encode($ergebnis);
?>