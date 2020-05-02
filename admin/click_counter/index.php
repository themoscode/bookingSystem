<?php
include_once ("../../php/db-config.php");

$myPDO = new PDO("mysql:host=$DBHost;dbname=$DBName","$DBUser","$DBPass",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$myPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

$sql = "
		SELECT * 
		FROM  stats
		ORDER BY id DESC ";

$PDOStatement = $myPDO->query($sql);
$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
$stats = $PDOStatement->fetchAll();

?>

<!DOCTYPE html>
<html>
	<head>
    	<meta name="description" content="website description">
    	<meta name="keywords" content="website keywords, website keywords">
    	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    	<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>	
		
	<body>
		<div id="tableClickCounter">
			<h2>Aufruf-Statistik für flowerpower-ibus.de</h2>
			<h4>Kontinuierliche Zugriffszahlen über Kooperationspartner mitfahrgelegenheit.de</h4>
			
			<table border="0">
				
			<?php
						echo "<thead>";
						echo "<tr>";
						echo "<th>Klick-Nr.</th>";
						echo "<th>Datum  /  Uhrzeit</th>";
						echo "<th>Adresse (URL)</th>";
						echo "</tr>";
						echo "</thead>";

 
			foreach ($stats as $index=>$stat):  
				echo "<tbody><tr><td>" . $stat['counter'] . "</td><td>" . $stat['date'] . "</td><td>" . $stat['url'] . "</td></tr></tbody>";
			
			endforeach;
			?>
			</table>
		</div>
	</body>
</html>