<?php 

require ('db.php');

$ergebnis = array(
"message" => "",
"result" => ""
);	

require ('citylist.sql.php');
echo json_encode($ergebnis);
?>
	