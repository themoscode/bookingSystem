<?php 

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

//check if exists///

$searchArr['name'] = trim($_POST['name']);


$sql = 'SELECT ID,name FROM city WHERE name=:name';
		
$PDOStatement = $myPDO->prepare($sql);

$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$city = $PDOStatement->fetchAll();
$counter = count($city);

if ($counter == 0) {
	
	
	$sql = 'INSERT INTO city (name) VALUES (:name)';
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	$ergebnis["message"] = "Die Stadt wurde erfolgreich gespeichert.";
	
	
	////////////////////////result////////////////////
	
	require ('citylist.sql.php');
	
	///////////////////end result/////////////////////////////////
		
}
else {

	$ergebnis["message"] = "Diese Stadt existiert bereits.";
	$ergebnis["result"] = "0";
}
		
echo json_encode($ergebnis);

?>