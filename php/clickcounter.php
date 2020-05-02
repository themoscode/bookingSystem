<?php



if(strpos ($_SERVER['HTTP_REFERER'], 'mitfahrgelegenheit.de') == true) {

	require("db-config.php");
	
	mysqli_connect ($DBHost, $DBUser, $DBPass);
	mysqli_select_db ($DBName) or die('Fehler: '. mysqli_error());
	
	
	$sql_select='SELECT counter 
				 FROM stats
				 ORDER BY id DESC
				 LIMIT 1';
					
	$result = mysqli_query($sql_select) or die (mysqli_error());
	
	$counter = mysqli_result($result,0);
	
	$counter++;
	
	$sql_insert ="INSERT INTO stats (counter, date, url) VALUES ( ". $counter.", NOW(),'".$_SERVER['HTTP_REFERER']."' )";
		
	mysqli_query($sql_insert) or die (mysqli_error());
}
?>
