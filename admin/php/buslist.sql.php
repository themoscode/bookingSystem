<?php

function isUsed($id,$myPDO){
	
	$arr['ID'] = $id;
	
	$sql = 'SELECT COUNT(bus.ID) as countUsed
	
	FROM bus
	
	JOIN unique_route ON unique_route.busID = bus.ID
	
	WHERE  bus.ID = :ID
	';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($arr);
	
	$row = $PDOStatement->fetch();
	if ($row['countUsed'] > 0) {
		return 'true';
	
	}
	return 'false';

}


	$sql = 'SELECT ID, busModelID,code,notes FROM bus 
	        WHERE busModelID =:busModelID 
			ORDER BY code ASC';
	
	$PDOStatement = $myPDO->prepare($sql);
	
	$searchArr2['busModelID'] = $_POST['busModelID'];
	
	$PDOStatement->execute($searchArr2);
	$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		
	$buses = $PDOStatement->fetchAll();
	
	$ergebnis["result"] = "<option data-used='false' value='0'>Kennzeichen</option>";
	
	foreach ($buses as $index=>$bus): 
	
		$ergebnis["result"] = $ergebnis["result"] . "<option data-used='".isUsed($bus['ID'],$myPDO)."' value='" . $bus['ID'] ."'>" . $bus['code'] . "</option>";
	
	endforeach;
?>