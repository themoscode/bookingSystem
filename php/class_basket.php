<?php 


class __basket{
	
	
	private function getFreeSeats($rsdetailID,$Global) {
	
		 
		$myPDO = $Global->dbOpen();
		
		$searchArr['id'] = $rsdetailID;
		$sql = 'SELECT freeSeats FROM route_station_details WHERE ID=:id';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		$result = $row["freeSeats"];
		return $result;
		
	}
	
	
	private function updateRideFreeSeats($rsdetailID,$newFreeSeats,$Global){
		
		//echo "bookfrom=".$bookFrom.",rideFrom=".$row["rideFrom"]."-- bookto=".$bookTo.",rideTo=".$row["rideTo"]."</br>";
		
		//echo "rsdetailID=".$rsdetailID;
		
		 
		$myPDO = $Global->dbOpen();
		
		$filter['rsdetailID'] = $rsdetailID; 
		$filter['newFreeSeats'] = $newFreeSeats; 
		
		$sql = 'UPDATE route_station_details SET freeSeats=:newFreeSeats WHERE ID=:rsdetailID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($filter);	
	
	}
	
	
	private function calculateNewFreeSeats($rsdetailID,$bookedSeats,$action,$Global){ // = private function calculateNewFreeSeats from admin
		
		 
		$myPDO = $Global->dbOpen();
		
		////find the uniquerouteID, rideFrom, rideTo
		
		$filter['ID'] = $rsdetailID;
		$sql='
	
		SELECT 

		route_station_details.uniqueRouteID,
		
		stationOrder1.stationOrder AS bookFrom,
		stationOrder2.stationOrder AS bookTo

		FROM 

		route_station_details 

		JOIN route_station_order stationOrder1 ON route_station_details.stationFromID = stationOrder1.stationID 
		AND route_station_details.routeID = stationOrder1.routeID 
		JOIN route_station_order stationOrder2 ON route_station_details.stationToID = stationOrder2.stationID
		AND route_station_details.routeID = stationOrder2.routeID 

		WHERE route_station_details.ID=:ID 

		';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($filter);
		$row = $PDOStatement->fetch();
		$uniqueRouteID = $row["uniqueRouteID"];
		$bookFrom = $row["bookFrom"];
		$bookTo = $row["bookTo"];
		
		////take all freeseats from route_station_details order by id where uniqueRouteID=$uniqueRouteID
		unset ($filter); 
		$filter['uniqueRouteID'] = $uniqueRouteID;
		
		$sql='
		
		SELECT 

		route_station_details.ID,
		route_station_details.routeID,
		route_station_details.uniqueRouteID,
		route_station_details.freeSeats, 

		route_station_details.stationFromID,
		route_station_details.stationToID,

		stationOrder1.stationOrder AS rideFrom,
		stationOrder2.stationOrder AS rideTo

		FROM 

		route_station_details 

		JOIN route_station_order stationOrder1 ON route_station_details.stationFromID = stationOrder1.stationID 
		AND route_station_details.routeID = stationOrder1.routeID 
		JOIN route_station_order stationOrder2 ON route_station_details.stationToID = stationOrder2.stationID
		AND route_station_details.routeID = stationOrder2.routeID 

		WHERE route_station_details.uniqueRouteID=:uniqueRouteID 

		ORDER BY route_station_details.ID ASC
		';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($filter);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		foreach ($rows as $index=>$row): 
			
			//if ($bookFrom >= $row["rideFrom"] && $bookTo <= $row["rideTo"]) { LATHOS LOGIKI
			
			if ( $row["rideFrom"] < $bookTo && $row["rideTo"] > $bookFrom ) {
				//echo "bookfrom=".$bookFrom.",rideFrom=".$row["rideFrom"]."-- bookto=".$bookTo.",rideTo=".$row["rideTo"].",,,,,,,,,,";
				
				if ($action == 'reserve') {
					$newFreeSeats = $row["freeSeats"] - $bookedSeats;
				}
				else if ($action == 'release') {
					$newFreeSeats = $row["freeSeats"] + $bookedSeats;
				}
				
				
				$this->updateRideFreeSeats($row["ID"],$newFreeSeats,$Global);
			}
			
		endforeach;
		
	}
	
	
	
	private function equalizeTimes($Global) {
		
		 
		$myPDO = $Global->dbOpen();
		
		$arr['sessionID'] = session_id();
		
		$sql = 'update basket set addTime = NOW( ) WHERE sessionID = :sessionID'; 
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
		
	}
	
	public function clearRemains($Global){
		
		 
		$myPDO = $Global->dbOpen();
		$result  = array();
		$arr['ID'] = 0;
		
		$sql = 'SELECT ID,routeStationDetailsID FROM basket WHERE addTime + INTERVAL '.$Global->clearBasketRemainsTime().' MINUTE < NOW( ) and ID > :ID ORDER BY ID'; 
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		foreach ($rows as $index=>$row): 
			$this->removeItem($row,$Global); 
		endforeach;	
		
		$result['status']='basket remains cleared';
		
		return $result;
	
	}
	
	
	public function clear($Global){
		
		 
		$myPDO = $Global->dbOpen();
		
		$arr['sessionID'] = session_id();
		
		$sql = 'SELECT ID,routeStationDetailsID FROM basket WHERE sessionID = :sessionID ORDER BY ID'; 
	
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		foreach ($rows as $index=>$row): 
			$this->removeItem($row,$Global); 
		endforeach; 
		
		
		$result['status']='basket cleared for sessionid';
		
		return $result;
	}
	
	private function getPrice($rsdetailID,$Global) {
		 
		$myPDO = $Global->dbOpen();
		$searchArr['id'] = $rsdetailID;
		$sql = 'SELECT price FROM route_station_details WHERE ID=:id';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		$result = $row["price"];
		return $result;
		
	}
	
	
	public function addItem($data,$Global) {
		
		$result  = array();
		
		if ($this->getFreeSeats($data['0'],$Global) < $data['1']+$data['2']) {
		
			$result['status']='no_free_seats';
			return $result;
		}
		
		 
		$myPDO = $Global->dbOpen();
		
		$result  = array();
		//$arr = array();
		
		$arr['sessionID'] = session_id();
		$arr['routeStationDetailsID'] = $data['0'];
		$arr['adults'] = $data['1'];
		$arr['children'] = $data['2'];
		
		$arr['price_total_adults'] = $data['3'];
		$arr['price_total_children'] = $data['4'];
		$arr['price'] = $this->getPrice($data['0'],$Global);
		
		
		$arr['stations'] = $data['5'];
		$arr['departure'] = $data['6'];
		
		
		$sql = 'INSERT INTO basket (sessionID,routeStationDetailsID,adults,children,price_total_adults,price_total_children,stations,departure,price) 
							VALUES (:sessionID,:routeStationDetailsID,:adults,:children,:price_total_adults,:price_total_children,:stations,:departure,:price)';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
		
		$result['status']='item added to basket';
		
		
		/**************************************/
		$this->calculateNewFreeSeats($data['0'],$data['1']+$data['2'],'reserve',$Global);
		/**************************************/
		
		/**************************************/
		$this->equalizeTimes($Global);
		/**************************************/
		
		return $result;
	
	}
	
	private function passengersNum($ID,$Global){
		
		 
		$myPDO = $Global->dbOpen();
		$arr  = array();
		$result = 0;
		
		$arr['ID'] = $ID;
		
		$sql = 'SELECT adults,children FROM basket WHERE ID=:ID';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$row = $PDOStatement->fetch();
		
		$result = $row['adults'] + $row['children'];
		
		return $result;
	} 
	
	
	
	public function removeItem($data,$Global) {
		
		/**************************************/
		$this->equalizeTimes($Global);
		/**************************************/
		/**************************************/
		$this->calculateNewFreeSeats($data['routeStationDetailsID'],$this->passengersNum($data['ID'],$Global),'release',$Global);
		/**************************************/
		
		 
		$myPDO = $Global->dbOpen();
		$result  = array();
		$arr = array();
		
		
		$arr['ID'] = $data['ID'];
		
		$sql = 'DELETE FROM basket WHERE ID=:ID'; 
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
		
		$result['status']='item deleted from basket';
		
		
		return $result;
		
	}
	
	public function show($Global){
		
		 
		$myPDO = $Global->dbOpen();
		
		$result  = array();
		$arr = array();
		
		$result['basket'] = '';
		
		if (!isset($_SESSION['CREATED'])) {
		
			//return $result;  // GIATI TO EIXA PALIA?????
		
		}
		
		
		$arr['sessionID'] = session_id();
		
		$sql='select basket.*
		
			FROM basket
			
			JOIN route_station_details ON basket.routeStationDetailsID = route_station_details.ID
			
			WHERE basket.sessionID = :sessionID
			
			ORDER BY  route_station_details.departure
			
			';
			
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		
		
		if ( count($rows)>0 ) {
			
			$result['basket'] = $result['basket'] . '<h1>WARENKORB</h1>';
			
			foreach ($rows as $index=>$row): 
				
				$strKind = 'Kind';
				$strAdult = 'Erwachsener';
				if ($row["adults"]>1) {$strAdult = 'Erwachsene';}
				
				$priceTotal = $row["price_total_adults"] + $row["price_total_children"];
				
				$result['basket'] = $result['basket'] . '<div id="basket_item_'.$row["routeStationDetailsID"].'" class="basket_item" data-price_total="'.$priceTotal.'" data-price="'.$row["price"].'" data-basket_id="'.$row["ID"].'"  data-id="'.$row["routeStationDetailsID"].'" data-adults="'.$row["adults"].'" data-children="'.$row["children"].'" data-stations="'.$row["stations"].'" data-departure="'.$row["departure"].'">';
				$result['basket'] = $result['basket'] . '<h2>'.$row["departure"].'<span style="float:right;cursor:pointer;" class="basket_remove_item" data-basket_id="'.$row["ID"].'" data-id="'.$row["routeStationDetailsID"].'">x</span></h2>';
				$result['basket'] = $result['basket'] . '<h4>'.$row["stations"].'</h4>';
				$result['basket'] = $result['basket'] . '<p class="price_total_adults" data-price="'.$row["price_total_adults"].'">'.$row["adults"].' '.$strAdult.'<span>'.$row["price_total_adults"].' €</span></p>';
			
				if ($row["children"]>0) {
					if ($row["children"]>1){$strKind='Kinder';}
					$result['basket'] = $result['basket'] . '<p class="price_total_children" data-price="'.$row["price_total_children"].'">'.$row["children"].' '.$strKind.'<span>'.$row["price_total_children"].' €</span></p>';
				}
				$result['basket'] = $result['basket'] . '</div>';
			
			endforeach;
			
				$result['basket'] = $result['basket'] .'<div id="basket_sum">';
					$result['basket'] = $result['basket'] .'<h3 id="basket_sum_num" data-totalprice="0">Summe: 0 €</h3>';
					$result['basket'] = $result['basket'] .'<div>(incl. MwSt.)</div>';
					$result['basket'] = $result['basket'] .'<input value="FAHRT(EN) BUCHEN" id="basket_to_book" type="button">';
				$result['basket'] = $result['basket'] .'</div>';
			
			
		}
			
			return $result;
	
	}


}

?>