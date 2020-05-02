<?php


class __search{

	
	private function getPreviousDate($data,$direction,$Global) {
		
		//$Global = new __Global();
	    $myPDO = $Global->dbOpen();
		
		////////////////previous date query
		$previousDateId = '';
		
		$searchArr['sumPassengers'] = $data['val_adults'] + $data['val_children'];  
		
		if ($direction == 'to') {
			
			$searchArr['dateDeperture'] = $Global->dmy_To_ymd($data['val_from-date']).' 00:00:00';
			$searchArr['stationFrom'] = trim($data['val_from-city-search']);
			$searchArr['stationTo'] = trim($data['val_to-city-search']);
			$previousDateId = 'previousDateTo';
		
		}
		
		else {
			
			$searchArr['dateDeperture'] = $Global->dmy_To_ymd($data['val_back-date']).' 00:00:00';
			$searchArr['stationFrom'] = trim($data['val_to-city-search']);
			$searchArr['stationTo'] = trim($data['val_from-city-search']);
			$previousDateId = 'previousDateBack';
		}
		
		$sql = '
		
		SELECT
				YEAR(route_station_details.departure) as yearDeparture,
				MONTH(route_station_details.departure) as monthDeparture,
				DAYOFMONTH(route_station_details.departure) as dayDeparture,
				route_station_details.departure
				
				
		FROM 	route_station_details 
		
		JOIN station station1 ON route_station_details.stationFromID = station1.ID 
		JOIN station station2 ON route_station_details.stationToID = station2.ID 
		JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID

		
		WHERE 	station1.name = :stationFrom 
				AND station2.name = :stationTo
				
				AND route_station_details.departure < :dateDeperture
				
				AND freeSeats >= :sumPassengers 
				
				AND route_station_details.departure >= NOW()
				
				AND unique_route.active = 1
				
				ORDER BY route_station_details.departure DESC
		   ';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		
		if (!$row){
			$previousDate = '';
		}
		else {
			$previousDate = '<li><a class="" href="#" id="'.$previousDateId.'">'.substr($Global->getDayName($row["departure"]),0,2).','.$Global->twoDigits($row["dayDeparture"].'.'.$row["monthDeparture"].'.'.$row["yearDeparture"],'.').'</a></li>';
	     
		}
		
		
		return $previousDate;
	
	}
	
	
	private function getNextDate($data,$direction,$Global){
		
		//$Global = new __Global();
		$myPDO = $Global->dbOpen();
		
		$nextDateId = '';
		
		$searchArr['sumPassengers'] = $data['val_adults'] + $data['val_children'];  
		
		if ($direction == 'to') {
			
			$searchArr['dateDeperture'] = $Global->dmy_To_ymd($data['val_from-date']).' 23:59:00';
			$searchArr['stationFrom'] = trim($data['val_from-city-search']);
			$searchArr['stationTo'] = trim($data['val_to-city-search']);
			$nextDateId = 'nextDateTo';
		}
		
		else {
			
			$searchArr['dateDeperture'] = $Global->dmy_To_ymd($data['val_back-date']).' 23:59:00';
			$searchArr['stationFrom'] = trim($data['val_to-city-search']);
			$searchArr['stationTo'] = trim($data['val_from-city-search']);
			$nextDateId = 'nextDateBack';
		}
		
		
		$sql = '
		
		SELECT
				YEAR(route_station_details.departure) as yearDeparture,
				MONTH(route_station_details.departure) as monthDeparture,
				DAYOFMONTH(route_station_details.departure) as dayDeparture,
				route_station_details.departure
				

		FROM 	route_station_details 
		
		JOIN station station1 ON route_station_details.stationFromID = station1.ID 
		JOIN station station2 ON route_station_details.stationToID = station2.ID 
		JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
		
						
		WHERE 	station1.name = :stationFrom 
				AND station2.name = :stationTo
				
				AND route_station_details.departure > :dateDeperture
				
				AND freeSeats >= :sumPassengers 
				
				AND route_station_details.departure >= NOW()
				
				AND unique_route.active = 1
				
				ORDER BY route_station_details.departure
		   ';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		
		if (!$row){
			$nextDate = '';
		}
		else {																			
			$nextDate = '<li><a class="" href="#" id="'.$nextDateId.'">'.substr($Global->getDayName($row["departure"]),0,2).','.$Global->twoDigits($row["dayDeparture"].'.'.$row["monthDeparture"].'.'.$row["yearDeparture"],'.').'</a></li>';
		
		}
		
		return $nextDate;
	
	}
	
	private function getCurrentDate($data,$direction,$Global) {
		
		$currentDateId = '';
		
		if ($direction == 'to') {
			$theDate = $data["val_from-date"];
			$currentDateId = 'currentDateTo';
		}
		else {
			$theDate = $data["val_back-date"];
			$currentDateId = 'currentDateBack';
		}
		
		
		$currentDate = '<li><a class="resultDatesActive" href="#" id="'.$currentDateId.'" >'.substr($Global->getDayName($theDate),0,2).','.$Global->twoDigits($theDate,'.').'</a></li>';
		return $currentDate;
	
	}
	
	
	
	private function getResultDatesTo($data,$Global){
		
		$previousDate = $this->getPreviousDate($data,'to',$Global);
		$currentDate = $this->getCurrentDate($data,'to',$Global);
		$nextDate = $this->getNextDate($data,'to',$Global);
		$prevSymbol = '<li><a class="" href="#" id="previousTo"><</a></li>';
		$nextSymbol = '<li><a class="" href="#" id="nextTo">></a></li>';
		
		if ($previousDate == '') {$prevSymbol = '';}
		if ($nextDate == '') {$nextSymbol = '';}	
		
		$result = $prevSymbol.$previousDate.$currentDate.$nextDate.$nextSymbol;
		
		return $result;
	}
	
	private function getResultDatesBack($data,$Global){
		
		if (isset($data['val_checkbox']) && $data['val_back-date']!='') {
			
			//substr($Global->getDayName($dateDeparture),0, 2)
			
			
			$previousDate = $this->getPreviousDate($data,'back',$Global);
			$currentDate = $this->getCurrentDate($data,'back',$Global);
			$nextDate = $this->getNextDate($data,'back',$Global);
			$prevSymbol = '<li><a class="" href="#" id="previousBack"><</a></li>';
			$nextSymbol = '<li><a class="" href="#" id="nextBack">></a></li>';
			
			if ($previousDate == '') {$prevSymbol = '';}
			if ($nextDate == '') {$nextSymbol = '';}	
			
			
			$result = $prevSymbol.$previousDate.$currentDate.$nextDate.$nextSymbol;
		}
		
		else {
			$result = "";
		}
		
		return $result;
	}
	
	private function getResultRides($data,$direction,$Global ){
		
		$result='';
		
		//$Global = new __Global();
		$myPDO = $Global->dbOpen();
		
		$searchArr['sumPassengers'] = $data['val_adults'] + $data['val_children']; 
		
		if ($direction == 'to') {
			
			$searchArr['stationFrom'] = trim($data['val_from-city-search']);
			$searchArr['stationTo'] = trim($data['val_to-city-search']);
			
			$searchArr['yearDeparture'] = $Global->year_from_dmy($data['val_from-date']);
			$searchArr['monthDeparture'] = $Global->month_from_dmy($data['val_from-date']);
			$searchArr['dayDeparture'] = $Global->day_from_dmy($data['val_from-date']);
			$dateDeparture = $data['val_from-date'];
		}
		else {
		
			$searchArr['stationFrom'] = trim($data['val_to-city-search']);
			$searchArr['stationTo'] = trim($data['val_from-city-search']);
			
			$searchArr['yearDeparture'] = $Global->year_from_dmy($data['val_back-date']);
			$searchArr['monthDeparture'] = $Global->month_from_dmy($data['val_back-date']);
			$searchArr['dayDeparture'] = $Global->day_from_dmy($data['val_back-date']);
			$dateDeparture = $data['val_back-date'];
		
		}
		
		$sql = '
		
		SELECT
				route_station_details.departure,
				
				YEAR(route_station_details.departure) as yearDeparture,
				MONTH(route_station_details.departure) as monthDeparture,
				DAYOFMONTH(route_station_details.departure) as dayDeparture,
				HOUR(route_station_details.departure) AS hourDeparture,
				MINUTE(route_station_details.departure) AS minuteDeparture,
				
				YEAR(route_station_details.arrival) as yearArrival,
				MONTH(route_station_details.arrival) as monthArrival,
				DAYOFMONTH(route_station_details.arrival) as dayArrival,
				HOUR(route_station_details.arrival) AS hourArrival,
				MINUTE(route_station_details.arrival) AS minuteArrival,
				
				route_station_details.freeSeats,
				route_station_details.price,
				route_station_details.ID

		FROM 	route_station_details 
		
		
		JOIN station station1 ON route_station_details.stationFromID = station1.ID 
		JOIN station station2 ON route_station_details.stationToID = station2.ID 
		JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID

		
		WHERE 	station1.name = :stationFrom 
				AND station2.name = :stationTo
				
				AND YEAR(route_station_details.departure) = :yearDeparture
				AND MONTH(route_station_details.departure) = :monthDeparture
				AND DAYOFMONTH(route_station_details.departure) = :dayDeparture
				
				AND freeSeats >= :sumPassengers 
				
				AND route_station_details.departure >= NOW()
				AND route_station_details.arrival >= NOW()
				
				AND unique_route.active = 1
				
				ORDER BY hourDeparture,minuteDeparture
		   ';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		if ( count($rows)>0 ) {
			
			
			$result = $result.'<table id="result-table" >            				
				<tr>
					<th>Von - Bis</th>
					<th>Plätze</th>
					<th>Preise</th>
					<th style="text-align:center;">Summe</th>
				</tr>';
			
           		
			foreach ($rows as $index=>$row): 
			  
			  $timeDeparture = $Global->twoDigits($row["hourDeparture"].':'.$row["minuteDeparture"],':');
			  $timeArrival = $Global->twoDigits($row["hourArrival"].':'.$row["minuteArrival"],':');
			  $priceTotalAdults = $data['val_adults']*$Global->price('adult',$row["price"]);
			  $priceTotalChildren= $data['val_children']*$Global->price('child',$row["price"]);
			 
			  $rideDeparture = strtotime($row['departure']);
			  $curtime = time();
			  $btn_booking='<input type="button" value="Buchen" class="btn_addToBasket" id="btn_addToBasket_'.$row["ID"].'" data-id="'.
					$row["ID"].'" data-adults="'.$data['val_adults'].
					'" data-children="'.$data['val_children'].
					'" data-price_total_adults="'.$priceTotalAdults.
					'" data-price_total_children="'.$priceTotalChildren.
					'" data-stations="'.$searchArr['stationFrom'].' → '.$searchArr['stationTo'].
					'" data-departure = "'.substr($Global->getDayName($dateDeparture),0, 2).'.'.$Global->day_from_dmy($dateDeparture).'.'.$Global->getMonthName($dateDeparture).','.$timeDeparture.'">';
			 
			 if(($rideDeparture - $curtime) < 10800) {
				$btn_booking='';
			  }
			 

			  $result = $result.'<tr>';
				  $result = $result.'<td>'.$timeDeparture.'h - '. $timeArrival.'h</td>';
				  $result = $result.'<td>'.$searchArr['sumPassengers'].'</td>';
				  $result = $result.'<td>';
				  
				  $result = $result.'<span>'.$data['val_adults'].' x '.$Global->price('adult',$row["price"]).'€</span>';
				  
				  if ($data['val_children'] > 0) {
					  $result = $result.'<br><span>'.$data['val_children'].' x '.$Global->price('child',$row["price"]).'€</span>';
					  }
				  
				  
     			  $result = $result.'</td>'; 	
				  $result = $result.'<td class="total">'.( $priceTotalAdults + $priceTotalChildren ).'€</td>';
				  $result = $result.'<td>'.$btn_booking.'</td>';
					
					
			 
			 $result = $result.'</tr>';
			
			endforeach;
			
			$result = $result.'</table>';
		}
		
		else {
		
			
			  $result= '';
			
		}
		
		return $result;
		
	}
	
	private function validStation($name,$Global){
	
		$myPDO = $Global->dbOpen();
		
		$arr['name']=$name;
		
		$sql='SELECT count( * ) AS numStations FROM station WHERE name=:name';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$row = $PDOStatement->fetch();
		
		if ($row['numStations'] == 0) {
			return false;
		}
		return true;
	}
	
	private function validStations($data,$Global){
	
		$stationFrom = trim($data['val_from-city-search']);
		$stationTo = trim($data['val_to-city-search']);
		
		if ($this->validStation($stationFrom,$Global) == false || $this->validStation($stationTo,$Global) == false) {
		
			return false;
		}
		return true;
	
	}
	
	public function getResults($data,$Global){
	
		$result  = array();
		
		$result['validStations'] = $this->validStations($data,$Global);
		
		$result['resultDatesTo'] = $this->getResultDatesTo($data,$Global);
		$result['resultRidesTo'] = $this->getResultRides($data,'to',$Global);
		
		$result['resultDatesBack'] = $this->getResultDatesBack($data,$Global);
		
		if (isset($data['val_checkbox']) && $data['val_back-date']!='') {
			$result['resultRidesBack'] = $this->getResultRides($data,'back',$Global);
		}
		else{
			$result['resultRidesBack'] ='';
		}
		
		return $result;
	}
	
}

?>