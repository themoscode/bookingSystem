<?php 

require ('db.php');
$ergebnis = array(
"message" => "",
"result" => ""
);	

require ('busmodellist.sql.php');
echo json_encode($ergebnis);

?>