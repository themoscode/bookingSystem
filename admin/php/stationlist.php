<?php 

if (! empty($_POST)) {

	require ('db.php');
	
	$ergebnis = array(
	"message" => "",
	"result" => ""
	);
	
	require ('stationlist.sql.php');
	echo json_encode($ergebnis);
}
?>
	