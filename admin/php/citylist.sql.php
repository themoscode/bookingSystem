<?php 
function isUsed($id,$myPDO){
	
	$arr['ID'] = $id;
	
	$sql = '
	SELECT COUNT(city.ID) as countUsed
	
	FROM station
	
	JOIN city ON city.ID = station.cityID
	JOIN route_station_details ON route_station_details.stationFromID = station.ID 
		OR route_station_details.stationToID = station.ID
	
	WHERE  city.ID = :ID
	';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($arr);
	
	$row = $PDOStatement->fetch();
	if ($row['countUsed'] > 0) {
		return 'true';
	
	}
	return 'false';

}




$sql = 'SELECT  ID, name  FROM city ORDER BY name ASC';
		
$PDOStatement = $myPDO->query($sql);

$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
	
//print_r ($PDOStatement->fetchAll());

//echo '<pre>' ; print_r ($PDOStatement->fetchAll()); echo '</pre>';

$cities = $PDOStatement->fetchAll();
$ergebnis["result"] = "<option value='0' data-used='false'>Stadt...</option>";

foreach ($cities as $index=>$city):
	
	$ergebnis["result"] = $ergebnis["result"] . "<option data-used='".isUsed($city['ID'],$myPDO)."' value='" . $city['ID'] ."'>" . $city['name'] . "</option>";
	
endforeach

?>