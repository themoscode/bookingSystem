
<?php
function isUsed($id,$myPDO){
	
	$arr['ID'] = $id;
	
	$sql = '
	SELECT COUNT(bus.ID) as countUsed
	
	FROM bus
	
	JOIN busmodel ON busmodel.ID = bus.busModelID
	JOIN unique_route ON unique_route.busID = bus.ID
	
	WHERE  busmodel.ID = :ID
	';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($arr);
	
	$row = $PDOStatement->fetch();
	if ($row['countUsed'] > 0) {
		return 'true';
	
	}
	return 'false';

}



$sql = 'SELECT  ID, model , numberOfSeats  FROM busmodel ORDER BY model ASC';
		
$PDOStatement = $myPDO->query($sql);

$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
	
//print_r ($PDOStatement->fetchAll());

//echo '<pre>' ; print_r ($PDOStatement->fetchAll()); echo '</pre>';

$busModels = $PDOStatement->fetchAll();

$ergebnis["result"] = "<option data-used='false' value='0'>(Bus-)Modelle</option>";
		
foreach ($busModels as $index=>$busModel): 
	
	$ergebnis["result"] = $ergebnis["result"] . "<option data-used='".isUsed($busModel['ID'],$myPDO)."' value='" . $busModel['ID'] ."'>" . $busModel['model'] . ", Plätze:" . $busModel['numberOfSeats'] . "</option>";
	
endforeach; 
	
?>