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
		<meta>
			<style>
				body { background-color:#fff; overflow:y-scroll; }
				body > table { position:absolute; width:500px; top:10%; left:30%; text-align:center; background-color:#eee; }
	    		body > table > tr > th { padding:5px 20px 5px 20px; }	
	    		body > table > tr > td { padding:5px 20px 5px 20px; }	
			</style>
		</meta>
	</head>
	<body>
		<table border="1">
		<?php
						echo "<tr>";
						echo "<th>Klick-Nr.</th>";
						echo "<th>Datum  /  Uhrzeit</th>";
						echo "<th>Adresse (URL)</th>";
						echo "</tr>";
 
			foreach ($stats as $index=>$stat):  
				echo "<tr><td>" . $stat['counter'] . "</td><td>" . $stat['date'] . "</td><td>" . $stat['url'] . "</td></tr>";
			endforeach;
		?>
		</table>
	</body>
</html>