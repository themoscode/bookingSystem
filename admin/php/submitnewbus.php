<?php

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

$searchArr['code'] = trim($_POST['code']);


$sql = 'SELECT count(ID) as counter FROM bus WHERE code=:code';

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$row = $PDOStatement->fetch();
$counter = $row['counter'];

if ($counter == 0) {
	
	$searchArr['busModelID'] = $_POST['busModelID'];
	$searchArr['notes'] = '';
	
	//echo "code=".$searchArr['code'];
	//echo "busModelID=".$searchArr['busModelID'];
	
	$sql = 'INSERT INTO bus (busModelID,code,notes) VALUES (:busModelID,:code,:notes)';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	$ergebnis["message"] = "Das Fahrzeug wurde erfolgreich gespeichert.";
	$ergebnis["result"] = "1";
	
	////////////////////////result////////////////////
	
	//require ('buslist.sql.php');
	
	///////////////////end result/////////////////////////////////
}
else {
	
	$ergebnis["message"] = "Dieses Fahrzeug existiert bereits.";
	$ergebnis["result"] = "0";

}

echo json_encode($ergebnis);

?>