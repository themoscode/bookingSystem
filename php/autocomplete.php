<?php
	
	require 'db-config.php';
	require 'class_autocomplete.php';

	$autocomplete = new __autocomplete();
	$autocomplete->dbopen($con,$CONFIG);

	if (isset($_GET['aktion']) && $_GET['aktion'] === 'readStationFrom') {
		echo json_encode($autocomplete->readStationFrom($_GET['term'],$con));
	}

	if (isset($_GET['aktion']) && $_GET['aktion'] === 'readStationTo') {   
		echo json_encode($autocomplete->readStationTo($_GET['term'],$_GET['idC'],$con));
	}
	
?>		