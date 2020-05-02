<?php 

if (! empty($_POST)) {

	require ('db.php');
	
	$ergebnis = array(
	"message" => "",
	"result" => ""
	);
	
	require ('buslist.sql.php');
	echo json_encode($ergebnis);
}
?>	