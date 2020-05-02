<?php

class __passengerList{
	
	private function dbOpen(){
		require ('db.php');
		return $myPDO;
	}
	
	private function dmy_To_ymd($str) {
		$dateArray = explode ("-", $str); 
		$result = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
		return $result;
	}
	
	private function getBookingNumber($year,$month,$day,$bookingID){
	
		$Buchungsnummer = $year.$this->twoDigits($month.':'.$day,':').'/0'.$bookingID;
		$Buchungsnummer = str_replace(':','',$Buchungsnummer);
		$Buchungsnummer = '#'.$Buchungsnummer;
		
		return $Buchungsnummer;
	
	}
	
    private function twoDigits($str,$delimeter) {

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
	
	
	public function getRides($data){
	
		$result='';
		$myPDO = $this->dbOpen();
		
		$arr['dateFrom'] = $this->dmy_To_ymd($data['dateFrom']).' 00:00:00';
		$arr['dateTo'] = $this->dmy_To_ymd($data['dateTo']).' 23:59:59';
		
		$today = getdate();
		$curDate = $today['mday'].'-'.$today['mon'].'-'.$today['year'];
		
		$sql='
		SELECT DISTINCT route_station_details.ID,
		
		stationFrom.name AS stationNameFrom,
		stationTo.name AS stationNameTo,
		
		CONCAT(DAYOFMONTH(route_station_details.departure), ".",MONTH(route_station_details.departure),".",YEAR(route_station_details.departure)) AS dateDeparture,
		CONCAT(DAYOFMONTH(route_station_details.arrival), ".",MONTH(route_station_details.arrival),".",YEAR(route_station_details.arrival)) AS dateArrival,
	
		CONCAT(HOUR(route_station_details.departure),":",MINUTE(route_station_details.departure)) AS timeDeparture,
		CONCAT(HOUR(route_station_details.arrival),":",MINUTE(route_station_details.arrival)) AS timeArrival,
		
		route_station_details.departure,
		
		unique_route.active
		
		FROM route_station_details
		
		JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
		JOIN station stationTo ON route_station_details.stationToID = stationTo.ID
		JOIN booking ON route_station_details.ID = booking.routeStationDetailsID
		JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
		
		WHERE route_station_details.departure >= :dateFrom
		AND route_station_details.departure <= :dateTo
		
		';
		
		if (isset($data['stationFrom']) && $data['stationFrom'] != '0') {
			
			$arr['stationFrom']=$data['stationFrom'];
			$sql.=' AND stationFromID=:stationFrom';
		
		}
		
		if (isset($data['stationTo']) && $data['stationTo'] != '0') {
			
			$arr['stationTo']=$data['stationTo'];
			$sql.=' AND stationToID=:stationTo';
		
		}
		
		$sql .=' ORDER BY route_station_details.departure,stationNameFrom,stationNameTo';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		$counter = 1;
		
		foreach ($rows as $index=>$row):
			
			$classPastRide="";
			$class_cancelled = '';
			
			$rideDeparture = strtotime($row['departure']);
			$curtime = time();
										
			if(($curtime - $rideDeparture) > 0) {
				$classPastRide="PastRide";
			}
			
			if ($row['active'] == -1) {
				$class_cancelled = 'passengerRides_cancelled';
			}
			
			$className = 'passengerRides'.' '.$classPastRide.' '.$class_cancelled;
			
			$result.= '
			<div class="'.$className.'" data-elmid="passListWrapper_'.$row['ID'].'_'.$counter.'">
				Passagierliste:'.$row['stationNameFrom'].' => '.$row['stationNameTo'].' ['.$this->twoDigits($row['dateDeparture'],'.').','.$this->twoDigits($row['timeDeparture'],':').' - '.$this->twoDigits($row['dateArrival'],'.').','.$this->twoDigits($row['timeArrival'],':').']
			</div>';
			
			$data['id']=$row['ID'];
			$data['dateDeparture']=$this->twoDigits($row['dateDeparture'],'.');
			$data['timeDeparture']=$this->twoDigits($row['timeDeparture'],':');
			$data['dateArrival']=$this->twoDigits($row['dateArrival'],'.');
			$data['timeArrival']=$this->twoDigits($row['timeArrival'],':');
			$data['stations'] = $row['stationNameFrom'].'->'.$row['stationNameTo'];
			$data['pastRide'] = $classPastRide;
			
			$result.= '<div class="passListWrapper" id="passListWrapper_'.$row['ID'].'_'.$counter.'" >'.$this->getPassengerList($data).'</div>';
			
			$counter++;
			
		endforeach;
		
		return $result;
		
	}
	
	public function getStations($flag) {
		
		$result='';
		
		$myPDO = $this->dbOpen();
		
		if ($flag == 'from') {
			$arr['ID']=0;
			$sql='SELECT DISTINCT route_station_details.stationFromID AS stationID,station.name as stationName
				FROM route_station_details
				JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
				JOIN booking ON route_station_details.ID = booking.routeStationDetailsID
				JOIN station ON route_station_details.stationFromID = station.ID
				WHERE route_station_details.ID > :ID
				ORDER BY route_station_details.stationFromID
				';
		}
		elseif ($flag == 'to') {
			$arr['stationFromID'] = $_POST['stationFromID'];
			$sql='SELECT DISTINCT route_station_details.stationToID AS stationID,station.name as stationName
				FROM route_station_details
				JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
				JOIN booking ON route_station_details.ID = booking.routeStationDetailsID
				JOIN station ON route_station_details.stationToID = station.ID
				WHERE route_station_details.stationFromID=:stationFromID';
		}	
		
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		$result.= '<option value="0">Station..</option>';
		
		foreach ($rows as $index=>$row):
			
			$result.= '<option value="'.$row['stationID'].'">'.$row['stationName'].'</option>';
		
		endforeach;
		
		return $result;
		
	}
	
	
	public function getSearchHeader(){
	
		$result['answer']='';
		$result['answer'] .= '
			<h3>PASSAGIERLISTEN</h3>
			<div id="passengerSearchHeader">
				<form id="passengerSearchForm">
					Von: <input type="text" maxlength="10" class="dateField_passenger" name="dateFrom" id="searchDateFromPP" value="">
					bis: <input type="text" maxlength="10" class="dateField_passenger" name="dateTo" id="searchDateToPP" value="">
					
					Von: <select id="searchStationFromPL" name="stationFrom">'.$this->getStations('from').'</select>
					Nach: <select id="searchStationToPL" name="stationTo"><option value="0">Station..</option></select>
					<input type="submit" value="Passagierlisten anzeigen" >
				</form>
			</div>
			<div id="passengerRides"></div>
		
		';
		return $result;
	}
	
	public function getPassengerList_pdf($data,$Global){
		
		$myPDO = $Global->dbOpen();
		
		
		$arr['routeStationDetailsID']=$data['id'];
		
		$sql='
		SELECT book_passenger.name AS passName, 
		book_passenger.surname AS passSurname, 
		book_passenger.mobileNumber as passMobileNumber, 
		book_passenger.price,
		booking.ID, 
		
		DAYOFMONTH(booking.bookTime) as dayBook,
		YEAR(booking.bookTime) as yearBook,
		MONTH(booking.bookTime) as monthBook,
		
		book_user.name AS userName, 
		book_user.surname AS userSurname,
		book_user.mobileNumber AS userMobileNumber
		
		FROM book_passenger
		JOIN booking ON book_passenger.bookingID = booking.ID
		JOIN book_user ON booking.book_userID = book_user.ID
		
		WHERE booking.bookStatus =1
		AND routeStationDetailsID =:routeStationDetailsID
		';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		//window.open('php/passengerlist.php?action=getPassengerList_pdf&id='+$obj.attr('data-id')+'&stations='+$obj.attr('data-stations')+'&dateDeparture='+$obj.attr('data-datedeparture')+'&timeDeparture='+$obj.attr('data-timedeparture')+'&dateArrival='+$obj.attr('data-datearrival')+'&timeArrival='+$obj.attr('data-timearrival'));
	
		
		$result ='';
		$result .= '
		
		<div style="width:100%; font:normal 18px arial, sans-serif;	">
	
			<header>
				<h1 style="margin:50px 0 0 0; padding:25px 0 10px 0; font-size:35px;">Passagierliste</h1>
				<div style="margin-top:10px;">'.$data['stations'].'</div>
				<div style="margin:0 0 50px 0;font:normal 14px arial, sans-serif;">'.$data['dateDeparture'].','.$data['timeDeparture'].'h - '.$data['dateArrival'].','.$data['timeArrival'].'h</div>
			</header>
		
		
			<table style="width:100%; padding:0;background-color:#fff;" border="0">
			
				<thead style="font-weight:bold;">
					<tr>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Vorname</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Nachname</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Handynummer</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Buchungskunde</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Kunden-Tel</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Buchungsnummer</td>
						<td style="border-bottom:1px solid #ccc;font-weight:bold;">Preis</td>
					</tr>	
				</thead>';	
		
	$result .= '
	            <tbody style="margin-top:5px;">';
				
				foreach ($rows as $index=>$row):
			
					$result.= '
					<tr class="passListRow" >
						<td>'.$row['passName'].'</td>
						<td>'.$row['passSurname'].'</td>
						<td>'.$row['passMobileNumber'].'</td>
						<td>'.$row['userName'].' '.$row['userSurname'].'</td>
						<td>'.$row['userMobileNumber'].'</td>
						<td>'.$this->getBookingNumber($row['yearBook'],$row['monthBook'],$row['dayBook'],$row['ID']).'</td>
						<td>'.$row['price'].' €'.'</td>
					</tr>
					';
		
				endforeach;
					
				$result.= '
				
				</tbody>
	
			</table>
			
		</div>
			';
	
		$mpdf = new mPDF('utf-8', array(210,297));
		$mpdf->WriteHTML($result);
		
		$res  = str_replace('>','',$data['stations'].'_'.$data['dateDeparture']);
		
		$filename = 'Passagierliste_'.$res.'.pdf';
		
		$mpdf->Output($filename,'D');
		
		$file_to_create = '../'.$Global->folder_pdf('passengerList').'/'.$filename;
		
		if (file_exists($file_to_create)) {
			unlink($file_to_create);
		}
		
		$mpdf->Output($file_to_create,'F');
		
		
	}
	
	
	
	public function getPassengerList($data){
		
		$myPDO = $this->dbOpen();
		
		$arr['routeStationDetailsID']=$data['id'];
		
		$sql='
		SELECT book_passenger.name AS passName, 
		book_passenger.surname AS passSurname, 
		book_passenger.mobileNumber as passMobileNumber, 
		book_passenger.price,
		booking.ID, 
		booking.bookStatus,
		DAYOFMONTH(booking.bookTime) as dayBook,
		YEAR(booking.bookTime) as yearBook,
		MONTH(booking.bookTime) as monthBook,
		
		book_user.name AS userName, 
		book_user.surname AS userSurname,
		book_user.mobileNumber AS userMobileNumber
		
		FROM book_passenger
		JOIN booking ON book_passenger.bookingID = booking.ID
		JOIN book_user ON booking.book_userID = book_user.ID
		
		WHERE routeStationDetailsID =:routeStationDetailsID
		';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		$result ='';
		$result .= '
			<table class="passListTable '.$data['pastRide'].'" border="0">
			
				<thead>
					<tr>
						<td>Vorname</td>
						<td>Nachname</td>
						<td>Handynummer</td>
						<td>Buchungskunde</td>
						<td>Kunden-Tel.</td>
						<td>Buchungsnummer</td>
						<td>Preis</td>
					</tr>	
				</thead>';	
		
	$result .= '
	            <tbody>';
				
				$rowsCount = count($rows);
				$rowsCancelled = 0;
				
				foreach ($rows as $index=>$row):
					$class='passListRow';
					if ($row['bookStatus']!=1) {
						$class='cancelled';
						$rowsCancelled = $rowsCancelled + 1;
						
					}
					
					$result.= '
					<tr class="'.$class.'" >
						<td>'.$row['passName'].'</td>
						<td>'.$row['passSurname'].'</td>
						<td>'.$row['passMobileNumber'].'</td>
						<td>'.$row['userName'].' '.$row['userSurname'].'</td>
						<td>'.$row['userMobileNumber'].'</td>
						<td>'.$this->getBookingNumber($row['yearBook'],$row['monthBook'],$row['dayBook'],$row['ID']).'</td>
						<td>'.$row['price'].' €'.'</td>
					</tr>
					';
		
				endforeach;
					
				$result.= '
				
				</tbody>
	
			</table>';
			
			if ($rowsCount != $rowsCancelled) {
			
				$result.= '
				<div class="passList_pdf_but" data-id="'.$data['id'].'" data-stations="'.$data['stations'].'" data-datedeparture="'.$data['dateDeparture'].'" data-timedeparture="'.$data['timeDeparture'].'" data-datearrival="'.$data['dateArrival'].'" data-timearrival="'.$data['timeArrival'].'"><img src="../images/pdf.jpg"></div>
				';
			
			}
			
	
	
	
		return $result;
	}

}

require ('../../php/class_global.php');
require ('../../php/mPDF/mpdf.php');


if (isset($_GET['action'])) {
	
	$P = new __passengerList();
	$G = new __Global();
	
	switch ($_GET['action']) {
		
		
		case 'getSearchHeader':
			echo json_encode($P->getSearchHeader($_POST));
			break;
			
		case 'getStationsTo':
			echo json_encode($P->getStations('to'));
			break;
			
		case 'getRides':
			echo json_encode($P->getRides($_POST));
			break;
			
		case 'getPassengerList_pdf':
			$P->getPassengerList_pdf($_GET,$G);
			break;
	
		
		default:
			break;
	}  
} 
?>
