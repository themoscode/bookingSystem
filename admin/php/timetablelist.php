<?php 
require ('db.php');

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


$today = getdate();
$curDate = $today['mday'].'-'.$today['mon'].'-'.$today['year'];
$curDate2 = $today['mday'].'-'.$today['mon'].'-'.($today['year']+1);

$searchDateFrom = twoDigits($curDate,'-');
$searchDateTo= twoDigits($curDate2,'-');

if( isset($_POST['searchDateFrom']) ){
							
	$searchDateFrom = twoDigits($_POST['searchDateFrom'],'-');
	$searchDateTo = twoDigits($_POST['searchDateTo'],'-');
}

$searchArr['searchDateFrom'] = dmy_To_ymd($searchDateFrom).' 00:00:00';
$searchArr['searchDateTo'] = dmy_To_ymd($searchDateTo).' 23:59:59';
$searchArr['ID'] = 0;		

$sql='SELECT
					
	route_station_details.routeID,
	route.name AS routeName,
	route_station_details.ID AS routeStationDetailsID,

	unique_route.active,

	unique_route.busID,
	busmodel.model AS busModel,
	busmodel.numberOfSeats AS busSeats,
	bus.code AS busCode,

	unique_route.ID as uniqueRouteID,

	route_station_details.stationFromID,
	route_station_details.stationToID,

	stationFrom.name AS stationNameFrom,
	stationTo.name AS stationNameTo,

	route_station_details.price,

	CONCAT(DAYOFMONTH(route_station_details.departure), "-",MONTH(route_station_details.departure),"-",YEAR(route_station_details.departure)) AS dateDeparture,
	CONCAT(DAYOFMONTH(route_station_details.arrival), "-",MONTH(route_station_details.arrival),"-",YEAR(route_station_details.arrival)) AS dateArrival,
	
	CONCAT(HOUR(route_station_details.departure),":",MINUTE(route_station_details.departure)) AS timeDeparture,
	CONCAT(HOUR(route_station_details.arrival),":",MINUTE(route_station_details.arrival)) AS timeArrival,
	
	route_station_details.departure,
	route_station_details.arrival,
	
	route_station_details.freeSeats,
	route_station_details.reservedSeats


	FROM route_station_details 

	JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
	JOIN route ON route_station_details.routeID = route.ID

	JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
	JOIN station stationTo ON route_station_details.stationToID = stationTo.ID
	JOIN bus ON unique_route.busID = bus.ID
	JOIN busmodel ON busmodel.ID = bus.busModelID
	
	WHERE route_station_details.ID > :ID
	
	AND unique_route.departure >=:searchDateFrom 
	AND unique_route.departure <=:searchDateTo 
	
	
	ORDER BY departure,stationNameFrom,stationNameTo 
	';	
	
	
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$timeTables = $PDOStatement->fetchAll();
		$countTimeTable = count($timeTables);
		?>
		<h3>FAHRPLÄNE</h3>
		<div id="tableHeader">
			Fahrpläne von: <input type="text" maxlength="10" class="dateField_timetable" id="searchDateFromTT" value="<?php echo $searchDateFrom;?>">
			bis: <input type="text" maxlength="10" class="dateField_timetable" id="searchDateToTT" value="<?php echo $searchDateTo;?>">
			<input type="button" value="Fahrpläne anzeigen" onclick="myTimeTable.getTimeTableList('search');">
		</div>
		<?php if ($countTimeTable > 0) {?>
			<table id="tableTimeTable">
				<thead>
				  <tr>
					<td id="gas"><strong>Abfahrt</strong></td>
					<td><strong>Ankunft</strong></td>
					<td><strong>Von</strong></td>
					<td><strong>Nach</strong></td>
					<td><strong>Bus</strong></td>
					<td><strong>Freie Plätze</strong></td>
					<td><strong>Preis</strong></td>
				  </tr>
				</thead>
				
			<?php foreach ($timeTables as $index2=>$timeTable):
					
					$classPastRide="";
					$classCanceledRide="";
					$activeRide="activeRide";
					
					$rideDeparture = strtotime($timeTable['departure']);
					$curtime = time();
					
					if(($curtime - $rideDeparture) > 0) {
						$classPastRide="PastRide";
						$activeRide="";
					}
					
					
					if ( $timeTable["active"] !=1 ){
						$classCanceledRide="cancelled";
						$activeRide="";
					}
					
					
					if ( $index2%2==0 ) {
						 $className="white"." ".$classPastRide.' '.$classCanceledRide; 
					}
						else {
						 $className="grey"." ".$classPastRide.' '.$classCanceledRide;
					}
					?>
				<tr onclick="myBooking.prepareBooking(this);" class="<?php echo $className; ?>"  data-activeride="<?php echo $activeRide;?>" data-freeseats="<?php echo $timeTable["freeSeats"];?>" data-price="<?php echo $timeTable["price"];?>" data-buscode="<?php echo $timeTable["busCode"];?>" data-busseats="<?php echo $timeTable["busSeats"];?>" data-busmodel="<?php echo $timeTable["busModel"];?>" data-stationnameto="<?php echo $timeTable["stationNameTo"];?>" data-stationnamefrom="<?php echo $timeTable["stationNameFrom"];?>" data-arrival="<?php echo twoDigits($timeTable["dateArrival"],'-');?>&nbsp;<?php echo twoDigits($timeTable["timeArrival"],':');?>" data-departure="<?php echo twoDigits($timeTable["dateDeparture"],'-');?>&nbsp;<?php echo twoDigits($timeTable["timeDeparture"],':');?>" data-id="<?php echo $timeTable["routeStationDetailsID"];?>" >
					<td ><?php echo twoDigits($timeTable["dateDeparture"],'-');?>&nbsp;<?php echo twoDigits($timeTable["timeDeparture"],':');?></td>
					<td><?php echo twoDigits($timeTable["dateArrival"],'-');?>&nbsp;<?php echo twoDigits($timeTable["timeArrival"],':');?></td>
					<td><?php echo $timeTable["stationNameFrom"];?></td>
					<td><?php echo $timeTable["stationNameTo"];?></td>
					<td><?php echo $timeTable["busModel"];?>, <?php echo $timeTable["busCode"];?></td>
					<td id="td_freeSeats_<?php echo $timeTable["routeStationDetailsID"];?>"><?php echo $timeTable["freeSeats"];?></td>
					<td><?php echo $timeTable["price"];?>&nbsp;€</td>
				</tr>
			
			<?php
			endforeach
?>
	</table>
	
<?php
}
?>