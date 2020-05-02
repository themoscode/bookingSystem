<?php

function dmy_To_ymd($str) {
	$dateArray = explode ("-", $str); 
	$result = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
	return $result;
}

function twoDigits($str,$delimeter) {

	$result="";
	$elm = "";
	
	$teile = explode($delimeter, $str);
	$length = count($teile);
	
	for ($x = 0; $x <$length ; $x++) {
	
		$elm = $teile[$x];
		if (strlen($elm) < 2 ) {
			
			$elm = '0'.$teile[$x];
		}
		$result = $result.$elm.$delimeter;
	
	}
	$last = $result[strlen($result)-1]; 
	if ($last == $delimeter) {
		$result = substr($result, 0, -1);
	}

	return $result;
}


function getBookingsNum($rsDids,$myPDO){

	$ids = explode(",",$rsDids);
	$num=0;
	
	for ($i=0;$i<count($ids)-1;$i++) {
		
		$arr['routeStationDetailsID'] = $ids[$i];
		$sql = 'SELECT count( * ) AS num
		FROM booking
		WHERE routeStationDetailsID=:routeStationDetailsID';

		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);

		$row = $PDOStatement->fetch();
		$num=$num + $row['num'];
	
	}
	
	return $num;
	
}


$sql='
SELECT 

route.ID as routeID,

station1.name as firstStation,
station2.name as lastStation,

route.numberOfStations

FROM route

JOIN station station1 ON route.firstStationID = station1.ID 
JOIN station station2 ON route.lastStationID = station2.ID 

ORDER BY route.ID ASC
';

$PDOStatement = $myPDO->query($sql);

$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

$routes = $PDOStatement->fetchAll();

$routesCount = count($routes);



$ergebnis["result"] = '<table id="routeMainTable">';

//if ($routesCount>0) {

					'';



$ergebnis["result"] = $ergebnis["result"] .
	
	'
	<tr>
	
		<td colspan="3">
			
			<table id="newRouteTable" >
				<tr>
					<td><legend>Linien</legend></td>
				</tr>
				<tr>
					<td  align=center><input type="button" value="Neue Linie eintragen" id="butNewRoute" class="butNewRoute"></td>
				</tr>
	<tr>
		<td>
		
			<fieldset class="fieldOff" id="newRoute">
					<table  id="tableNewRoute">
						
						<tr>
							<td>Haltestellen';
							
							$sql="
									SELECT count( ID ) as numberOfStations
									FROM station";
									
									$PDOStatement = $myPDO->query($sql);
									$row = $PDOStatement->fetch();
									$numberOfStations = $row['numberOfStations'];
							
							$ergebnis["result"] = $ergebnis["result"] .	
							'
							
								<select class="selectNumberOfStations"  data-numberofstations = "'.$numberOfStations.'"   id="selectNumberOfStations" >';
									
									//take max number of stations
									
									$ergebnis["result"] = $ergebnis["result"] .	
										
										'<option value="0">Anzahl...</option>';
								
									for ($i=2;$i<=$numberOfStations;$i++) {
									
										$ergebnis["result"] = $ergebnis["result"] .	
										
										'<option value="'.$i.'">'.$i.'</option>';
									
									}
								
								$ergebnis["result"] = $ergebnis["result"] .	
								
									'
								</select>
								
							</td>
							
							
						</tr>
						<tr>
							<td id="tdSelectStations" class="fieldOff">';
							
							
							// give me so many list boxes as numberOfStations
							
							$sql = 'SELECT ID, name FROM station 
									ORDER BY name ASC';
							
							$PDOStatement = $myPDO->query($sql);
							
							$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
								
							$stations = $PDOStatement->fetchAll();
							
							
							for ($i=1;$i<=$numberOfStations;$i++) {
							
								$ergebnis["result"] = $ergebnis["result"] . $ergebnis["result"] = '<p class="fieldOff" id="StationList_'.$i.'" >Haltestelle Nr.'.$i.' '.'<select id="StationListBox_'.$i.'" >';
							
								foreach ($stations as $index=>$station): 
								
									$ergebnis["result"] = $ergebnis["result"] . "<option value='" . $station['ID'] ."'>" . $station['name'] . "</option>";
								
								endforeach;
								
								$ergebnis["result"] = $ergebnis["result"] . $ergebnis["result"] = '</select></p>';
							}
							
							
							
							$ergebnis["result"] = $ergebnis["result"] .	
							
							'
								
							</td>
						</tr>
						<tr>
							<td><input type="button" value="Linie speichern" id="butSaveNewRoute" disabled></td>
						</tr>
						
					</table>
					
			</fieldset>
		
		</td>
	</tr>
			
			</table>
		
		</td>
	
	</tr>';
	

			
	foreach ($routes as $index=>$route): 
				
				
				//take number of single rides for a given number of stations of a route.
				$numSingleRides = 0;
				
				for ($p=0;$p<$route['numberOfStations'];$p++){
					for ($i=$p+1;$i<$route['numberOfStations'];$i++){
						$numSingleRides=$numSingleRides+1;
					}
				}
				
				
				$searchArr4['routeID'] = $route['routeID'];
					
				
				///check if exists unique routes on this route
					$sql4="
						SELECT count( ID ) as numberOfUniqueRoutes
						FROM unique_route 
						WHERE
						routeID=:routeID
						";
						
					$PDOStatement4 = $myPDO->prepare($sql4);
					$PDOStatement4->execute($searchArr4);
					
					$row4 = $PDOStatement4->fetch();
					$numberOfUniqueRoutes = $row4['numberOfUniqueRoutes'];
				
				
				
				
				$ergebnis["result"] = $ergebnis["result"] .
						'<tr>
							
							<td>'.$route['firstStation'].'</td>
							<td>'.$route['lastStation'].'</td>
							
							<td>
								<input type="button" value="Haltestellen" id="butRouteStationOrder_'.$route['routeID'].'" class="butRouteStationOrder" data-id="'.$route['routeID'].'">';
								$today = getdate();
								$curDate = $today['mday'].'-'.$today['mon'].'-'.$today['year'];
								$curDate2 = $today['mday'].'-'.$today['mon'].'-'.($today['year']+1);
								
								$searchDateFrom = twoDigits($curDate,'-');
								$searchDateTo= twoDigits($curDate2,'-');
								
								if ($numberOfUniqueRoutes != 0) {
									
									if( isset($_POST['searchDateFrom']) ){
									
										$searchDateFrom = $_POST['searchDateFrom'];
										$searchDateTo = $_POST['searchDateTo'];
									}
									
									
									$ergebnis["result"] = $ergebnis["result"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="[--]" id="routeDetails_'.$route['routeID'].'" class="routeDetails" data-id="'.$route['routeID'].'">';
									$ergebnis["result"] = $ergebnis["result"] . 'Fahrten von: <input type="text" class="dateField_search" id="searchDateFrom_'.$route['routeID'].'" value="'.$searchDateFrom.'">
									bis: <input type="text" class="dateField_search" id="searchDateTo_'.$route['routeID'].'" value="'.$searchDateTo.'"> 
									<input type="button" value="Datenbankabfrage" id="uniqueRouteSearch_'.$route['routeID'].'" onclick=myRoute.getRouteList("'.$route['routeID'].'");>
									';
								}
				$ergebnis["result"] = $ergebnis["result"] .
								'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Neue Fahrt eintragen" id="newUniqueRoute_'.$route['routeID'].'" onclick="myRoute.newUniqueRoute('.$route['routeID'].');">
							</td>
						</tr>';
				
				
					
					$searchArr3['routeID'] = $route['routeID'];
					
					$sql3='
					
					SELECT 

						route.ID AS routeID,
						route.name AS routeName,
						route_station_order.ID AS routeStationOrderID,
						route_station_order.stationOrder,
						station.ID AS stationID,
						station.name AS stationName


						FROM route_station_order

						JOIN route ON route_station_order.routeID = route.ID
						JOIN station ON route_station_order.stationID = station.ID
						WHERE route.ID =:routeID
						ORDER BY route_station_order.stationOrder
					
					';
					
					$PDOStatement3 = $myPDO->prepare($sql3);
					$PDOStatement3->execute($searchArr3);
				
					$PDOStatement3->setFetchMode(PDO::FETCH_ASSOC);

					$routeStationOrders = $PDOStatement3->fetchAll();
					$countRouteStationOrders = count($routeStationOrders);
					
					if ($countRouteStationOrders > 0) {
						
						$stationOrderStr = '';
						
						$ergebnis["result"] = $ergebnis["result"] .	
						
							'<tr>
								<td colspan="3">
									<fieldset class="fieldOff" id="routeStationOrder_'.$route['routeID'].'">
										
										<table>
											<tr>
												<th></th>
												<th></th>
												
											</tr>';
						
						foreach ($routeStationOrders as $index3=>$routeStationOrder): 
							
							$stationOrderStr = $stationOrderStr . $routeStationOrder['stationName'].' >> ';
							
							$ergebnis["result"] = $ergebnis["result"] .	
										   '<tr>
												<td>
													'.$routeStationOrder['stationOrder'].'.
												</td>
												<td>'.$routeStationOrder['stationName'].'</td>
												
												
										   </tr>
									   
									   ';
						
						endforeach;
					
					$ergebnis["result"] = $ergebnis["result"] .	
					
									'</table>
								</fieldset>
							</td>
						</tr>';
					
					}
				
				
				////////////////
				
				$searchArr['routeID'] = $route['routeID'];
				$searchArr['searchDateFrom'] = dmy_To_ymd($searchDateFrom).' 00:00:00';
				$searchArr['searchDateTo'] = dmy_To_ymd($searchDateTo).' 23:59:59';
				
				
				$sql2='SELECT
					
					route_station_details.routeID,
					route.name AS routeName,
					route_station_details.ID AS routeStationDetailsID,
					
					unique_route.active,
					unique_route.busID,
					unique_route.ID as uniqueRouteID,
					
					route_station_details.stationFromID,
					route_station_details.stationToID,
					
					stationFrom.name AS stationNameFrom,
					stationTo.name AS stationNameTo,
					
					route_station_details.price,
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
					
					route_station_details.arrival,
					route_station_details.freeSeats,
					route_station_details.reservedSeats
					

					FROM route_station_details 

					INNER JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
					INNER JOIN route ON route_station_details.routeID = route.ID
					
					INNER JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
					INNER JOIN station stationTo ON route_station_details.stationToID = stationTo.ID
					WHERE route.ID = :routeID 
					AND unique_route.departure >=:searchDateFrom 
					AND unique_route.departure <=:searchDateTo 
					
					ORDER BY unique_route.departure DESC, route_station_details.ID ASC
					
					
					
					';
					//ORDER BY unique_route.departure, unique_route.arrival
					
					
				
				$PDOStatement2 = $myPDO->prepare($sql2);
				$PDOStatement2->execute($searchArr);
				
				$PDOStatement2->setFetchMode(PDO::FETCH_ASSOC);

				$routeStationDetails = $PDOStatement2->fetchAll();
				$countRouteStationDetails = count($routeStationDetails);
			
			if ($countRouteStationDetails > 0) {		
					
					////show buses////
					$sql='
					SELECT  
						bus.ID,
						bus.code,
						busmodel.model , 
						busmodel.numberOfSeats  
						
						FROM bus
						
						join busmodel ON bus.busModelID = busmodel.id
						ORDER BY model ASC
					';
					$PDOStatement = $myPDO->query($sql);
					$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
					$buses = $PDOStatement->fetchAll();
					$fieldClass = "fieldOff";
					if( isset($_POST['routeID']) ){
						if ($_POST['routeID'] == $route['routeID']) {
							$fieldClass = "fieldOn";
						}
						
					}
					
					
					$ergebnis["result"] = $ergebnis["result"] .		
						
						'<tr>
							<td colspan="3">
								<fieldset class="'.$fieldClass.'" onOff ="on" id="routeStationDetails_'.$route['routeID'].'">
									<legend class="routeListHeader">Fahrten der Linie: '.$stationOrderStr.' </legend>
									<table>
										<tr>
											<th>VON</th>
											<th>NACH</th>
											<th>ABFAHRT</th>
											<th>ANKUNFT</th>
											<th>PLÄTZE&nbsp;&nbsp;&nbsp;</th>
											<th>PREIS</th>
										</tr>
										
										';
								$routeStationDetailsIds = '';		
								
								$routeStationDetailCounter = 1;
								$headerDetailsCounter = 1;
								
								foreach ($routeStationDetails as $index2=>$routeStationDetail): 
										
										//////departure prepare
										
										$yearDeparture = $routeStationDetail['yearDeparture'];
										$monthDeparture = $routeStationDetail['monthDeparture'];
										$dayDeparture = $routeStationDetail['dayDeparture'];
										
										$hourDeparture = $routeStationDetail['hourDeparture'];
										$minuteDeparture = $routeStationDetail['minuteDeparture'];
										
										$departureDate = $dayDeparture.'-'.$monthDeparture.'-'.$yearDeparture;
										$departureTime = $hourDeparture.':'.$minuteDeparture;
										
										///////arrival prepare
										
										$yearArrival = $routeStationDetail['yearArrival'];
										$monthArrival = $routeStationDetail['monthArrival'];
										$dayArrival = $routeStationDetail['dayArrival'];
										
										$hourArrival = $routeStationDetail['hourArrival'];
										$minuteArrival = $routeStationDetail['minuteArrival'];
										
										$arrivalDate = $dayArrival.'-'.$monthArrival.'-'.$yearArrival;
										$arrivalTime = $hourArrival.':'.$minuteArrival;
										
										$uniqueRouteID = $routeStationDetail['uniqueRouteID'];
										$routeStationDetailsIds = $routeStationDetailsIds . $routeStationDetail['routeStationDetailsID'].',';
										
										$disabled = ' ';
										$disabled_price=' ';
										
										$saveButtons = '
										<input type="button" value="Fahrt speichern" data-routeid = "'.$route['routeID'].'"  data-routeStationDetailsIds="'.$routeStationDetailsIds.'" data-uniquerouteid = "'.$uniqueRouteID.'"  class="editRouteStationDetailBut"  id="editRouteStationDetailBut_'.$uniqueRouteID.'">
										<input type="button" value="Fahrt online stellen" data-routeid = "'.$route['routeID'].'"  data-routeStationDetailsIds="'.$routeStationDetailsIds.'" data-uniquerouteid = "'.$uniqueRouteID.'"  class="editRouteStationDetailBut_online"  id="editRouteStationDetailBut_online_'.$uniqueRouteID.'">';
										
										$saveButtonStyle = '';
										
										if ($routeStationDetail['active'] == 1) {
											$disabled = ' disabled ';
											$saveButtons = '<b style="color:green;">ONLINE</b>
											<input type="button" value="Fahrt speichern" data-routeid = "'.$route['routeID'].'"  data-routeStationDetailsIds="'.$routeStationDetailsIds.'" data-uniquerouteid = "'.$uniqueRouteID.'"  class="editRouteStationDetailBut"  id="editRouteStationDetailBut_'.$uniqueRouteID.'">
											<input type="button" value="Fahrt stornieren" data-routeid = "'.$route['routeID'].'"  data-routeStationDetailsIds="'.$routeStationDetailsIds.'" data-uniquerouteid = "'.$uniqueRouteID.'"  class="editRouteStationDetailBut_cancel"  id="editRouteStationDetailBut_cancel_'.$uniqueRouteID.'" data-bookings="'.getBookingsNum($routeStationDetailsIds,$myPDO).'">';
										}
										elseif ($routeStationDetail['active'] == -1) {
											$disabled = ' disabled ';
											$disabled_price=' disabled ';
											$saveButtons = '<b style="color:red;">STORNIERT</b>';
											$saveButtonStyle = 'style="display:none"';
										} 
										
									$ergebnis["result"] = $ergebnis["result"] .		
										
										
										'<tr >
											<td class="uniqueRoute_'.$routeStationDetail['uniqueRouteID'].'">'.$routeStationDetail['stationNameFrom'].'</td>
											<td class="uniqueRoute_'.$routeStationDetail['uniqueRouteID'].'">'.$routeStationDetail['stationNameTo'].'</td>
									
											<td>
												<input type="text" '.$disabled.' readonly data-flag="data-datestationfromid" data-uniquerouteid="'.$routeStationDetail['uniqueRouteID'].'" data-datestationfromid'.$routeStationDetail['uniqueRouteID'].'="'.$routeStationDetail['stationFromID'].'"  size="12" class="dateField"   id="departureDate_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" name="departureDate_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" value="'.twoDigits($departureDate,'-').'"/>
												<input type="text" '.$disabled.' size="12"  data-timestationfromid'.$routeStationDetail['uniqueRouteID'].'="'.$routeStationDetail['stationFromID'].'" onkeyup=cloneValue(this,"data-timestationfromid'.$routeStationDetail['uniqueRouteID'].'",0); id="departureTime_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" name="departureTime_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" value="'.twoDigits($departureTime,':').'"/>
											</td>
											<td>
												<input type="text" '.$disabled.' readonly data-flag="data-datestationtoid" data-uniquerouteid="'.$routeStationDetail['uniqueRouteID'].'" data-datestationtoid'.$routeStationDetail['uniqueRouteID'].'="'.$routeStationDetail['stationToID'].'" size="12" class="dateField" id="arrivalDate_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" name="arrivalDate_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" value="'.twoDigits($arrivalDate,'-').'"/>
												<input type="text" '.$disabled.' size="12" data-timestationtoid'.$routeStationDetail['uniqueRouteID'].'="'.$routeStationDetail['stationToID'].'" onkeyup=cloneValue(this,"data-timestationtoid'.$routeStationDetail['uniqueRouteID'].'",0); id="arrivalTime_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" name="arrivalTime_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" value="'.twoDigits($arrivalTime,':').'"/>
											</td>
											
											
											<td data-tdseatsid="'.$routeStationDetail['uniqueRouteID'].'">'.$routeStationDetail['freeSeats'].'</td>
											<td><input type="text" '.$disabled_price.' size="12" class="priceField" id="price_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" name="price_'.$route['routeID'].'_'.$routeStationDetail['routeStationDetailsID'].'" value="'.$routeStationDetail['price'].'"/></td>
											
											
										</tr>';
										$uniqueRouteBusID = $routeStationDetail['busID'];
										
										
										
										if ($routeStationDetailCounter >= $numSingleRides) {
											
											$ergebnis["result"] = $ergebnis["result"] .		
												'<tr >
													<td colspan=7 align="left">
														Fahrzeug:
														<select '.$disabled.' id="busID_'.$uniqueRouteID.'"  >';
															$activebus	= '';		
															foreach ($buses as $index=>$bus): 
																	$busSelected = '';
																	if ($uniqueRouteBusID === $bus["ID"] ) {$busSelected='selected';}
																	$ergebnis["result"] = $ergebnis["result"] . '<option  data-seats="'.$bus["numberOfSeats"].'" value="'.$bus["ID"].'" '.$busSelected.'>'.$bus["model"].', id:'.$bus["ID"].', Code:'.$bus["code"].', Seats:'.$bus["numberOfSeats"].'</option>';	
															endforeach;			
															
															$ergebnis["result"] = $ergebnis["result"] .			
													   '</select>
													</td>
												</tr>';
												
												$ergebnis["result"] = $ergebnis["result"] .	
												
												'<tr><td colspan=7 align="center">'.$saveButtons.'</td></tr>';
												
												$headerDetailsCounter = $headerDetailsCounter+1;
												
												
												if ($headerDetailsCounter<=$numberOfUniqueRoutes) {
												
													$ergebnis["result"] = $ergebnis["result"] .	'
													<tr>
														<td colspan="6"><hr></td>
													</tr>
													<tr class="von_nach_header">
														<th>VON</th>
														<th>NACH</th>
														<th>ABFAHRT</th>
														<th>ANKUNFT</th>
														<th>PLÄTZE&nbsp;&nbsp;&nbsp;</th>
														<th>PREIS</th>
													</tr>';
												
												
												}
												
												$routeStationDetailsIds = '';
												$routeStationDetailCounter=0;
												
											}
										
										$routeStationDetailCounter=$routeStationDetailCounter+1;
										
								endforeach;	
								
								$ergebnis["result"] = $ergebnis["result"] .	'</table>
								</fieldset>
							</td>
						</tr>';
						
						
					}
			//}
		
	endforeach; 
//}
$ergebnis["result"] = $ergebnis["result"] .	'</table>';

//echo $ergebnis["result"];
