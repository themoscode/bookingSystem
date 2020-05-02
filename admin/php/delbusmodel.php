<?php 


require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	



$sql = 'DELETE FROM busmodel WHERE ID=:ID';

$searchArr['ID'] = $_POST['ID'];

$PDOStatement = $myPDO->prepare($sql);
$PDOStatement->execute($searchArr);	


		
///////////////////////////////SHOW RESULTS/////////////////////		

	
require ('busmodellist.sql.php');

$ergebnis["message"]= "Modell erfolgreich gelöscht.";	

echo json_encode($ergebnis);




?>