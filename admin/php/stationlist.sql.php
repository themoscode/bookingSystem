<?php
function isUsed($id,$myPDO){
	
	$arr['ID'] = $id;
	
	$sql = '
	SELECT COUNT(station.ID) as countUsed
	
	FROM station
	
	JOIN route_station_details ON route_station_details.stationFromID = station.ID 
		OR route_station_details.stationToID = station.ID
	
	WHERE  station.ID = :ID
	';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($arr);
	
	$row = $PDOStatement->fetch();
	if ($row['countUsed'] > 0) {
		return 'true';
	
	}
	return 'false';

}


	$sql = 'SELECT ID, name FROM station 
	        WHERE cityID =:cityID 
			ORDER BY name ASC';
	
	$PDOStatement = $myPDO->prepare($sql);
	
	$searchArr2['cityID'] = $_POST['cityID'];
	
	$PDOStatement->execute($searchArr2);
	$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		
	//print_r ($PDOStatement->fetchAll());

	//echo '<pre>' ; print_r ($PDOStatement->fetchAll()); echo '</pre>';

	$stations = $PDOStatement->fetchAll();
	
	$ergebnis["result"] = "<option value='0' data-used='false'>Haltestelle...</option>";
	
	foreach ($stations as $index=>$station): 
	
		$ergebnis["result"] = $ergebnis["result"] . "<option data-used='".isUsed($station['ID'],$myPDO)."' value='" . $station['ID'] ."'>" . $station['name'] . "</option>";
	
	endforeach;
	
	
	

?>