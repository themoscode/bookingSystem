<?php 

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);

$searchArr['ID'] = $_POST['ID'];
$searchArr['name'] = $_POST['name'];

//check if exists//

$sql = 'SELECT ID,name FROM city WHERE name=:name and ID <> :ID';
		
$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$city = $PDOStatement->fetchAll();
$counter = count($city);

//end check if exists
$ergebnis["message"] = "message test";
$ergebnis["result"] = "counter=".$counter;

if ($counter == 0) {
	
	$sql = 'UPDATE city SET name=:name WHERE ID=:ID';

	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($searchArr);	
	
	//$ergebnis["message"] = "update ok"; 
	//$ergebnis["result"] = "update ok result"; 
	
	///////////////////////////////SHOW RESULTS/////////////////////		
	require ('citylist.sql.php');
	$ergebnis["message"]= "Die Stadt wurde erfolgreich aufgenommen.";	
	}
	
	else {

	$ergebnis["message"] = "Diese Stadt existiert bereits.";
	$ergebnis["result"] = "0";
	}

echo json_encode($ergebnis);




?>