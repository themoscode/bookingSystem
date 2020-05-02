<?php 


class __booking {
	
	private function dbOpen(){
		require ('db.php');
		return $myPDO;
	}
	
	private function generate_ticket($Global,$bookingID){
	
		$myPDO = $Global->dbOpen();
		
		$arr['bookingID'] = $bookingID;
		
		$sql='
		SELECT 
		
		booking.ID,
		YEAR(booking.bookTime) as yearBookTime, 
		MONTH(booking.bookTime) as monthBookTime,
		DAY(booking.bookTime) as dayBookTime,
		
		YEAR(route_station_details.departure) as yearDeparture, 
		MONTH(route_station_details.departure) as monthDeparture,
		DAY(route_station_details.departure) as dayDeparture,
		HOUR(route_station_details.departure) as hourDeparture,
		MINUTE(route_station_details.departure) as minuteDeparture,
		
		YEAR(route_station_details.arrival) as yearArrival, 
		MONTH(route_station_details.arrival) as monthArrival,
		DAY(route_station_details.arrival) as dayArrival,
		HOUR(route_station_details.arrival) as hourArrival,
		MINUTE(route_station_details.arrival) as minuteArrival,
		
		booking.adultPassengers, 
		booking.childPassengers, 
		booking.priceTotal, 
		
		route_station_details.price, 
		station1.name AS stationNameFrom, 
		station2.name AS stationNameTo,
		
		route_station_details.departure,
		route_station_details.arrival
		
		FROM booking
		
		JOIN route_station_details ON route_station_details.ID = booking.routeStationDetailsID
		JOIN station station1 ON route_station_details.stationFromID = station1.ID
		JOIN station station2 ON route_station_details.stationToID = station2.ID
		WHERE booking.ID =:bookingID';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$booking_details = $PDOStatement->fetch();
		
		$html = '
<body style="font-family: Arial, sans-serif; color:#555; background-color:#FFFFFF;" >
		
	<div id="pageWrapper" style="padding-top:-2%; background-color:#fff;" >
		
		<div id="bookingConfirmation" style="height:450px; width:100%; margin:0 auto 20px auto; padding:0;">
			
			<header style="width:100%; height:70px;">
				<img src="../../images/_logo_print.png" style="float:left; max-width:180; margin-top:20px;">  
				
				<h1 style="float:none; margin:0 auto 0 32px; font:bold 24px arial,sans-serif; letter-spacing:-0.5px; color:#666;">
					Buchungsbestätigung
				</h1>
				
				<div class="bookingDate" style="float:right; text-align: right; margin-top:-50px; font-family: Arial, sans-serif;">
					<p style="font-family: Arial, sans-serif;  font-size:8px;">BUCHUNGSDATUM:<br>
					<span style="font-family: Arial, sans-serif; font-size:10px; font-weight:bold;">'.$Global->twoDigits($booking_details['dayBookTime'].'.'.$booking_details['monthBookTime'].'.'.$booking_details['yearBookTime'],'.').'</span></p>
				</div>			
			</header>
			
			<hr style="background-color:#93d051;">
			
			<div style="width:100%; margin-top:15px;" >
			
					<div style="float:left; width:29%; height:30%; padding:0 10px 10px 10px; text-align:left; background:#F8F8F8;">
						<h2 style="margin-bottom:20px; font:normal 22px arial,sans-serif; letter-spacing:-0.5px; color:#555;"> 								
							Fahrgäste
						</h2>';
						
						$sql = 'select name, surname FROM book_passenger WHERE  type = 1 AND bookingID=:bookingID';
						
						$PDOStatement = $myPDO->prepare($sql);
						$PDOStatement->execute($arr);
						$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
						$names = $PDOStatement->fetchAll();

						foreach ($names as $index=>$name): 
						
							$html .='<div class="adultPassName">'.$name['name'].' '.$name['surname'].'</div>';
						
						endforeach;
						
						if ($booking_details['childPassengers'] >0) {
						
							$sql = 'select name, surname FROM book_passenger WHERE  type = 2 AND bookingID=:bookingID';
						
							$PDOStatement = $myPDO->prepare($sql);
							$PDOStatement->execute($arr);
							$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
							$names = $PDOStatement->fetchAll();

							foreach ($names as $index=>$name): 
							
								$html .='<div class="childPassName">'.$name['name'].' '.$name['surname'].'</div>';
							
							endforeach;
						
						}
						
					$dateDeparture0 = $Global->twoDigits($booking_details['dayDeparture'].'.'.$booking_details['monthDeparture'].'.'.$booking_details['yearDeparture'],'.');
					$dateDeparture = substr($Global->getDayName($dateDeparture0),0, 2).'.'.$Global->day_from_dmy($dateDeparture0).'.'.$Global->getMonthName($dateDeparture0).'.'.$booking_details['yearDeparture'];
					$dateDeparture_noDayName = $Global->day_from_dmy($dateDeparture0).'.'.$Global->getMonthName($dateDeparture0).'.'.$booking_details['yearDeparture'];
					
					
					$timeDeparture = $Global->twoDigits($booking_details['hourDeparture'].':'.$booking_details['minuteDeparture'],':');
								
						
					$html .='</div>
		
					<div style="width:29%; height:30%; margin-left:34%; padding:0 10px 10px 10px; background:#F8F8F8;">
						
						<div class="from_dates">
							<h2 style="margin-bottom:20px; font:normal 22px arial, sans-serif; letter-spacing:-0.5px; color:#555;">'.$dateDeparture_noDayName.'
							</h2>
							<label style="xxfont-size:12px; color:#444;">Ab '.$timeDeparture.'h</label>
							<h2 style="margin-top:-1px; font:normal 20px arial, sans-serif; color:#555;">'.$booking_details['stationNameFrom'].'</h2>
						</div>';
						
						$dateArrival = $Global->twoDigits($booking_details['dayArrival'].'.'.$booking_details['monthArrival'].'.'.$booking_details['yearArrival'],'.');
						$dateArrival = substr($Global->getDayName($dateArrival),0, 2).'.'.$Global->day_from_dmy($dateArrival).'.'.$Global->getMonthName($dateArrival).'.'.$booking_details['yearArrival'];
						$timeArrival = $Global->twoDigits($booking_details['hourArrival'].':'.$booking_details['minuteArrival'],':');
						
						$Buchungsnummer = $booking_details['yearDeparture'].$Global->twoDigits($booking_details['monthDeparture'].':'.$booking_details['dayDeparture'],':').'/0'.$bookingID;
						$Buchungsnummer = str_replace(':','',$Buchungsnummer);
						
						$html .='<div class="to_dates">
							<label style="color:#444;">An '.$timeArrival.'h</label>
							<h2 style="margin-top:-1px; font:normal 20px arial, sans-serif; color:#555;">'.$booking_details['stationNameTo'].'</h2>
						</div>					
						
					</div>
					
					<div style="float:right; margin-top:-310px; width:29%; height:30%; padding:0 10px 8px 10px;background:#F8F8F8;">
						<div class="bookingNumber" style="margin:6px 0 0 44%; padding:0; color: #555;">
							<p style="text-align:right; font:normal 12px arial, sans-serif;">Buchungsnummer:<br>
							<span style="text-align:right; font:bold 12px arial, sans-serif;">#'.$Buchungsnummer.'</span></p>
						</div>
						
						<div>
							<p style="padding-top:-10px; font-size:11px;">Diese Buchungsbestätigung bringen Sie bitte in ausgedruckter oder in elektronischer Form (PDF) zur Fahrt mit.</p>
							<p style="padding-top:-2px; font-size:11px;">Bitte seien Sie ca.15 min. vor Abfahrt an der Busstation. Ihre Fahrkarte verfällt - falls nicht eingelöst - 5 min. vor Abfahrt.</p>
							<p style="padding-top:-2px; font-size:11px;">Um Ihre Buchung zu stornieren, 
								rufen Sie rechtzeitig unsere Hotline unter: 069/ 260 93 262 oder 0172 / 45 40 087 an.</p>
							<p style="font-size:11px;">FAQ:
								<a href="http://www.flowerpower-ibus.de/FAQ.html" target="_blank">www.flowerpower-ibus.de/faq</a>
							</p>
						</div>
					</div>
			</div>
			
			<footer>
					<p style="text-align:right; font:normal 8px arial, sans-serif; color:#444;">
					   Es gelten die Allgemeinen Geschäfts- und Beförderungsbedingungen der Flowerpower iBus GmbH.
					</p>
			</footer>
		
		</div><!--end bookingConfirmation-->
			
	
	   	<h1 style="width:100%; margin:100px 0 20px 0; font:bold 24px arial, sans-serif; text-align:center; color:#444;">
	   		Fahrkarte
	   	</h1>
	   	<figure style="width:200%; height:1px; margin-bottom:25px; border-top:1px dashed #ccc;"></figure>
	   
	   	<div id="ticket" style="width:100%; border:0.5px solid #ccc; border-radius:7px;" >
			
			<header style="width:100%; height:65px; margin:0; padding:0; border-bottom:1px solid #ccc; background:#F1F1F1;" >
				<img src="../../images/_logo_print.png" style="max-width:200; max-height:100%; margin:0; padding:7px 20px 0 10px;" >
				<img src="../../images/stationticket.png" style="float:right; height:40; width:40; margin:12px 15px 10px 0;" >		
			</header>
		
			<article class="ticketdata" style="float:left; width:70%; margin-left:20px;">
			
				<div class="from_dates" style="padding:30px 10px 0 0;">
						
					<div style="float:left; width:40%; margin:0 10px 7px 0;">
						<label style="font-size:12px; font-weight:bold; color:#93D051; cursor:default;">Von</label>
						<br>
						<h2 style="margin:3px 0 0 0; font: normal 18px arial, sans-serif;">
							'.$booking_details['stationNameFrom'].'
						</h2>
						<hr style="margin-top:20px;" >
					</div>
					
					<div style="float:right; width:58%; margin:0 0 7px 20px; ">
						<label style="font:bold 12px arial, sans-serif; color:#93D051;">Abfahrt</label>
						<br>
						<h2 style="margin:3px 0 0 0; font:normal 18px arial, sans-serif;">
							'.$dateDeparture.','.$timeDeparture.'h
						</h2>	
						<hr style="margin-top:20px;" >
					</div>
				</div>
				
				<div class="to_dates" style="margin-top:-20px; padding:10px 10px 10px 0;">
					
					<div style="float:left; width:40%; margin:0 10px 7px 0;" >
						<label style="font:bold 12px arial, sans-serif; color:#93D051;" >Nach</label>
					 	<br>
						<h2 style="margin:3px 0 0 0; font:normal 18px arial, sans-serif;">
							'.$booking_details['stationNameTo'].'
						</h2>
					</div>
						
					<div style="float:right; width:58%; margin-left:20px;" >
						<label style="font:bold 12px arial, sans-serif; color:#93D051;">Ankunft</label>
					 	<br>
						<h2 style="margin-top:3px; font:normal 18px arial, sans-serif;">
							'.$dateArrival.','.$timeArrival.'h
						</h2>		
					</div>
					<hr style="width:100%; margin:7px; ">
				</div>
			
				<section style="width:100%; margin-top:-20px; padding:10px 10px 0 0;">
					
					<p style="margin:0; padding:0; font-size:8px; font-family:default;">
					Diese Fahrkarte gilt ausschliesslich für die jeweils gebuchte bzw. reservierte Fahrt (ohne Rückfahrt)
					und die o.g. Anzahl von Fahrgästen. Es gelten die Allgemeinen Geschäfts- und Beförderungsbedingungen 							der Flowerpower iBus GmbH.</p> 
					<p style="margin:0; padding-top:5px; font-size:7px; font-family:default;">
					Flowerpower iBus GmbH | Bockenheimer Landstr.17/19 | 60325 Frankfurt am Main | Tel.: 069 / 260 93 262 | 						www.flowerpower-ibus.de</p>
				
				</section>
			
			</article>';
			
			$strAdults = 'Erwachsener';
			if ($booking_details['adultPassengers']>1){$strAdults = 'Erwachsene';}
				
			$strKids = 'Kind';
			if ($booking_details['childPassengers']>1){$strKids = 'Kinder';}
			
			$html .='<aside class="ticketSidebox" style="float:right; width:22%; padding:30px 20px 0 10px; border-left:1px dashed #ccc;">';
		
			$html .='<div>
			  	
			  	<h2 style="margin:0; font-size:14px; font-weight:normal; color:#555;">Fahrgäste</h2>
				
				<table style="font:bold 12px arial, sans-serif; color:#555;">
					<tr>
						<td style="width:50%; height:25.5px; padding-top:8px; font-weight:normal;">';
							if ($booking_details['childPassengers'] >0) {
								$html .= $booking_details['childPassengers'].' '.$strKids;
							}
			            $html .='</td>
						<td style="width:50%; height:25.5px; padding:8px -2px 0 0; text-align:right;">';   
							if ($booking_details['childPassengers'] >0) { 
								$html .= $booking_details['childPassengers']*$Global->price('child',$booking_details['price']).' €';
							}
												
				    	$html .='</td>
				  	</tr>
				  	<tr>	
				    	<td style="width:50%; font-weight:normal;">
				    		'.$booking_details['adultPassengers'].' '.$strAdults.'
				    	</td>
						<td style="width:50%; padding-right:-2px; text-align:right;">
							'.$booking_details['adultPassengers']*$Global->price('adult',$booking_details['price']).' €
						</td>
				  	</tr>
				</table>
				
				<hr style="width:100%; margin:1px 0 4px 0;">
				<div class="sumPrice" style="text-align:right; margin:0 0 44px 0; font:bold 14px arial, sans-serif; color:#555;">
				'.$booking_details['priceTotal'].'  €
				</div>
					
			 </div>
						
			<div id="info" style="padding-bottom:7px;">
				<div style="text-align:right; font:normal 8px arial,sans-serif;">
					* Der Fahrpreis ist vor Beginn der Fahrt am Bus zu entrichten.
				</div>
			</div>
				
				<div class="bookingNumber" style="width:100%; text-align:right;">
					<p style="float:right; padding-top:3px; font-size:8px;">BUCHUNGSNUMMER:<br>
					<span style="font:bold 10px arial, sans-serif;">#'.$Buchungsnummer.'</span></p>
				</div>				
			</aside>	
			
		</div><!--end ticket-->
	   	
	</div><!--end pageWrapper-->		
	
</body>';
		
	
		$result['html'] = $html;
		
	
		$result['Buchungsnummer'] = '#'.str_replace("/","_",$Buchungsnummer);
		
		return $result;
		
	
	}
	
	
	
	private function confirmation_ticket_pdf($Global,$bookingID){
		
		$ticket = $this->generate_ticket($Global,$bookingID);
		
		
		$html = $ticket['html'];
		$Buchungsnummer = $ticket['Buchungsnummer'];
		
		unset($mpdf);

		$mpdf = new mPDF('utf-8', array(210,297));
		$mpdf->WriteHTML($html);
		
		$file_to_create = '../../'.$Global->folder_pdf('ticket').'/Buchungsbestaetigung_'.$Buchungsnummer.'.pdf';
		
		if (file_exists($file_to_create)) {
			unlink($file_to_create);
		}
		
		$content = $mpdf->Output($file_to_create,'F');

		$content = chunk_split(base64_encode($content));
		$mailto = 'themos.kost@yahoo.gr';
		$from_name = 'themos';
		$from_mail = 'themos.kost@yahoo.gr';
		$replyto = 'themos.kost@yahoo.gr';
		$uid = md5(uniqid(time()));
		$subject = 'hi this is the subject';
		$message = 'hi this is the message';
		$filename = 'Buchungsbestaetigung_'.$Buchungsnummer.'.pdf';

		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		$header .= "Reply-To: ".$replyto."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $message."\r\n\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$header .= $content."\r\n\r\n";
		$header .= "--".$uid."--";
		$is_sent = @mail($mailto, $subject, "", $header);

		//exit;

	}
	
	
	public function prepareBookingForm($data){
		require 'booking.tpl.php';
	}
	
	private function insertBookUser($data){
		//print_r ($data['adultName'][0]);
		
		$myPDO = $this->dbOpen();
		
		$searchArr['name'] = $data['name'];
		$searchArr['surname'] = $data['surname'];
		$searchArr['mobileNumber'] = $data['mobileNumber'];
		$searchArr['email'] = $data['mail'];
		$searchArr['street'] = $data['street'];
		$searchArr['streetNumber'] = $data['streetNumber'];
		$searchArr['city'] = $data['city'];
		$searchArr['postCode'] = $data['postCode'];
		$searchArr['sex'] = $data['sex'];
		$searchArr['form'] = $data['form'];
		 
		$sql = 'INSERT INTO book_user 
		(name,surname,mobileNumber,email,street,streetNumber,city,postCode,sex,form) 
		VALUES 
		(:name,:surname,:mobileNumber,:email,:street,:streetNumber,:city,:postCode,:sex,:form)';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);	
		$last_id = $myPDO->lastInsertId('book_user');
		
		return $last_id;
		
	}
	
	
	
	
	
	private function insertBooking($data,$lastBookUserID,$bookStatus,$bookAdmin){
	
		$myPDO = $this->dbOpen();
		
		$searchArr['book_userID'] = $lastBookUserID;
		$searchArr['routeStationDetailsID'] = $data['routeStationDetailsID'];
		$searchArr['adultPassengers'] = $data['adultPassengers'];
		$searchArr['childPassengers'] = $data['childPassengers'];
		$searchArr['priceTotal'] = $data['priceTotal'];
		$searchArr['bookStatus'] = $bookStatus; //1 reserved, 2 booked, 3 canceled
		$searchArr['bookAdmin'] = $bookAdmin; //1 admin, 2 user
		
		$sql = 'INSERT INTO booking 
		(book_userID,routeStationDetailsID,adultPassengers,childPassengers,priceTotal,bookStatus,bookAdmin) 
		VALUES 
		(:book_userID,:routeStationDetailsID,:adultPassengers,:childPassengers,:priceTotal,:bookStatus,:bookAdmin)';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);	
		$last_id = $myPDO->lastInsertId('booking');
		
		return $last_id;
		
	}
	
	
	private function updateRideFreeSeats($rsdetailID,$newFreeSeats){
		
		//echo "bookfrom=".$bookFrom.",rideFrom=".$row["rideFrom"]."-- bookto=".$bookTo.",rideTo=".$row["rideTo"]."</br>";
		
		//echo "rsdetailID=".$rsdetailID;
		
		$myPDO = $this->dbOpen();
		
		$filter['rsdetailID'] = $rsdetailID; 
		$filter['newFreeSeats'] = $newFreeSeats; 
		
		$sql = 'UPDATE route_station_details SET freeSeats=:newFreeSeats WHERE ID=:rsdetailID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($filter);	
	
	}
	
	private function calculateNewFreeSeats($rsdetailID,$bookedSeats,$action){
		
		$myPDO = $this->dbOpen();
		
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
			
			if ( $row["rideFrom"] < $bookTo && $row["rideTo"] > $bookFrom ) {
			//if ($bookFrom >= $row["rideFrom"] && $bookTo <= $row["rideTo"]) { //LATHOS 
				//echo "bookfrom=".$bookFrom.",rideFrom=".$row["rideFrom"]."-- bookto=".$bookTo.",rideTo=".$row["rideTo"].",,,,,,,,,,";
				
				if ($action == 'reserve') {
					$newFreeSeats = $row["freeSeats"] - $bookedSeats;
				}
				else if ($action == 'release') {
					$newFreeSeats = $row["freeSeats"] + $bookedSeats;
				}
				
				$this->updateRideFreeSeats($row["ID"],$newFreeSeats);
			}
			
		endforeach;
		
	}
	
	private function getFreeSeats($rsdetailID) {
	
		$myPDO = $this->dbOpen();
		$searchArr['id'] = $rsdetailID;
		$sql = 'SELECT freeSeats FROM route_station_details WHERE ID=:id';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);
		$row = $PDOStatement->fetch();
		$result = $row["freeSeats"];
		return $result;
		
	}
	
	private function insertPassenger($name,$surname,$type,$bookingID,$price,$mobileNumber){
		
		$myPDO = $this->dbOpen();
		
		$searchArr['name'] = $name;
		$searchArr['surname'] = $surname;
		$searchArr['type'] = $type;
		$searchArr['bookingID'] = $bookingID;
		$searchArr['price'] = $price;
		$searchArr['mobileNumber'] = $mobileNumber;
		
		$sql = 'INSERT INTO book_passenger 
		(name,surname,type,bookingID,price,mobileNumber) 
		VALUES 
		(:name,:surname,:type,:bookingID,:price,:mobileNumber)';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($searchArr);	
	
	}
	
	
	private function insertPassengers($data,$lastBookID,$Global){
	
		$adultPassengers = $data['adultPassengers'];
		$childPassengers = $data['childPassengers'];
		$adultprice = $Global->price('adult',$data['price']);
		$childprice = $Global->price('child',$data['price']);
		
		///insert adults
		for ($i=0;$i<$adultPassengers;$i++) {
			$this->insertPassenger($data['adultName'][$i],$data['adultSurname'][$i],1,$lastBookID,$adultprice,$data['passMobileNumber'][$i]);
		}
		///insert kids
		if ($childPassengers > 0) {
		
			for ($i=0;$i<$childPassengers;$i++) {
				$this->insertPassenger($data['kidName'][$i],$data['kidSurname'][$i],2,$lastBookID,$childprice,'');
			}
		
		
		}
			
	}
	
	private function spamMailCheck($field) {
		  // Sanitize e-mail address
		  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
		  // Validate e-mail address
		  if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
			return TRUE;
		  } else {
			return FALSE;
		  }
		}
	
	private function mailToBookUser($data,$Global){
		
		$from="themos.kost@yahoo.gr";
		
		$mailcheck = $this->spamMailCheck($from);
		if ($mailcheck==FALSE) {
		  //echo "Invalid input";
		} 

		else {
		 
		 $to="themos.kost@yahoo.gr";
		 $subject = "Buchungsdetails";
		
		  
		 $totalPrice = $data["priceTotal"]; 
		 $childPrice = $Global->price('child',$data['price']);
		 $adultPrice = $Global->price('adult',$data['price']);
		 
		 $message='';
		  
		 $message .= '<p><u><b>Von:'.$data['stationnamefrom'].', Abfahrt:'.$data['departure'].'</b></u></p>';
		 $message .= '<p><u><b>Nach:'.$data['stationnameto'].', Ankunft:'.$data['arrival'].'</b></u></p>';
		 $message .= '<p><u><b>Summe:'.$totalPrice.'</b></u></p>';
		 
		 $message .= '<p><u><b>Buchungskunde:</b></u></p>';
		 
		 
		 $message .= '<table>';
		 $message .='<tr>';
			$message .='<td>Vorname:</td>';
			$message .='<td>'.$data["name"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>Nachname:</td>';
			$message .='<td>'.$data["surname"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>Tel.-Nummer:</td>';
			$message .='<td>'.$data["mobileNumber"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>E-Mail:</td>';
			$message .='<td>'.$data["mail"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>Strasse:</td>';
			$message .='<td>'.$data["street"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>Hausnr.:</td>';
			$message .='<td>'.$data["streetNumber"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>PLZ:</td>';
			$message .='<td>'.$data["postCode"].'</td>';
		 $message .='</tr>';
		 $message .='<tr>';
			$message .='<td>Ort:</td>';
			$message .='<td>'.$data["city"].'</td>';
		 $message .='</tr>';
		 $message .= '</table>';
		 
		 $message .= '<p><u><b>Passagiere:</b></u></p>';
		 $message .= '<p><u>-Erwachsene:</u></p>';
		 
		 $message .= '<table>';
		 for ($i=0;$i<$data["adultPassengers"];$i++) {
			$message .='<tr>';
				$message .='<td>'.$data["adultName"][$i].'</td>';
				$message .='<td>'.$data["adultSurname"][$i].'</td>';  
				$message .='<td>Tel:'.$data["passMobileNumber"][$i].'</td>';
				$message .='<td>Preis:'.$adultPrice.'</td>';  
			$message .='</tr>';	
		}
		$message .= '</table>';
		
		if ($data["childPassengers"] > 0) {
		 
		  $message .= '<p><u>-Kinder:</u></p>';
		  $message .= '<table>';
		  
		  for ($i=0;$i<$data["childPassengers"];$i++) {
			$message .='<tr>';
				$message .='<td>'.$data["kidName"][$i].'</td>';
				$message .='<td>'.$data["kidSurname"][$i].'</td>';
				$message .='<td>Preis:'.$childPrice.'</td>';
			$message .='</tr>';	
			}
		 
		 $message .= '</table>';	
		
		}
		 
		 
		 $headers = "MIME-Version: 1.0" . "\r\n";
		 $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		 $headers .= "From: $from" . "\r\n";
		 
		  // message lines should not exceed 70 characters (PHP rule), so wrap it
		  //$message = wordwrap($message, 70);
		  // send mail
		  mail($to,$subject,$message,$headers);
		  
		  return $message;

		}
	
	}
	
	
	public function getBookings($data){
		$myPDO = $this->dbOpen();
		
		$filter['ID'] = $data['routeStationDetailsID'];
		
		$sql = '
		SELECT 

		booking.*,DATE(bookTime) as dateBookTime,
		book_user.*
		
		FROM booking
		
		JOIN book_user ON booking.book_userID = book_user.ID
		

		WHERE booking.routeStationDetailsID=:ID
		';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($filter);
		$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $PDOStatement->fetchAll();
		
		$result['rows'] = $rows;
		$result['freeSeats'] = $this->getFreeSeats($data['routeStationDetailsID']);
		
		return $result;
	}
	
	
	private function changeBookingStatus($bookStatus,$ID){
	
		$myPDO = $this->dbOpen();
		$arr['ID']=$ID;
		$arr['bookStatus']=$bookStatus;
		
		$sql = 'UPDATE booking SET bookStatus=:bookStatus WHERE ID=:ID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
	}
	
	
	
	public function cancelBooking($data){
		
		$result  = array();
	//change booking status
		$this->changeBookingStatus(3,$data['ID']);
	//release seats
		$this->calculateNewFreeSeats($data['routeStationDetailsID'],$data['passengers'],'release'); // calculate new free seats and update DB
		
		$result['msg'] = 'Die Buchung wurde erfolgreich storniert.';
		$result['freeSeats'] = $this->getFreeSeats($data['routeStationDetailsID']);
		
		return $result;
		
	}
	
	private function getAdults($data,$Global){
	
	$myPDO = $Global->dbOpen();
	
	$filter['bookingID'] = $data['ID'];
	
	$sql = 'select book_passenger.* FROM book_passenger WHERE bookingID=:bookingID AND type=1';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($filter);
	$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $PDOStatement->fetchAll();
	$html = '';
	$x=1;
		foreach ($rows as $index=>$row): 
						
			$html .='
			
			<ul><li>
					<input class="adultName" name="adultName[]"  value="'.$row['name'].'" maxlength="30" placeholder="Vorname" autocomplete="off" type="text">
					<input name="adultSurname[]" maxlength="30" class="adultSurname"  value="'.$row['surname'].'" placeholder="Nachname" autocomplete="off" type="text">
					<input name="passMobileNumber[]" maxlength="30" class="passMobileNumber"  value="'.$row['mobileNumber'].'" placeholder="Mobilnummer" autocomplete="off" type="text">';
					if ($x > 1) {
						$html .='<input type="button" class = "passDelete" value="löschen">';
					
					}
			$html .='</li></ul>
			
			';
			$x=$x+1;
		endforeach;
		
		return $html;
	
	}
	
	
	private function getKids($data,$Global){
	
	$myPDO = $Global->dbOpen();
	
	$filter['bookingID'] = $data['ID'];
	
	$sql = 'select book_passenger.* FROM book_passenger WHERE bookingID=:bookingID AND type=2';
	
	$PDOStatement = $myPDO->prepare($sql);
	$PDOStatement->execute($filter);
	$PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $PDOStatement->fetchAll();
	$html = '';
	$x=1;
		foreach ($rows as $index=>$row): 
						
			$html .='
			
			<ul><li>
					<input class="kidName" name="kidName[]"  value="'.$row['name'].'" maxlength="30" placeholder="Vorname" autocomplete="off" type="text">
					<input name="kidSurname[]" maxlength="30" class="kidSurname" value="'.$row['surname'].'" placeholder="Nachname" autocomplete="off" type="text">';
					$html .='<input type="button" class = "passDelete" value="löschen">';
			$html .='</li></ul>
			
			';
			$x=$x+1;
		endforeach;
		
		
		
		return $html;
	
	}
	
	private function getClientData($data,$Global) {
	
	
		$myPDO = $Global->dbOpen();
		
		$arr['book_userID'] = $data['book_userID'];
		
		$sql = 'select * FROM book_user WHERE ID=:book_userID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$row = $PDOStatement->fetch();
		
		$txt_frau = '';if ($row['sex'] == 'Frau') {$txt_frau = 'checked';}
		$txt_herr = '';if ($row['sex'] == 'Herr') {$txt_herr = 'checked';}
		$txt_priv = '';if ($row['form'] == 'Person') {$txt_priv = 'checked';}
		$txt_comp = '';if ($row['form'] == 'Firma') {$txt_comp = 'checked';}
		
		$html = '
		
				<li>
					<input name="sex" value="Frau" id="txt_frau" class="input_radio" '.$txt_frau.' type="radio"><span>Frau</span>
					<input name="sex" value="Herr" id="txt_herr" class="input_radio" '.$txt_herr.' type="radio"><span>Herr</span>	
				</li>
				<li>
					<input name="form" value="Person" id="txt_priv" class="input_radio" '.$txt_priv.' type="radio"><span>Person</span>
					<input name="form" value="Firma" id="txt_comp" class="input_radio" '.$txt_comp.' type="radio"><span>Firma</span>				
				</li>
				<br>
				<li>
					<input name="name" id="txt_name" value="'.$row['name'].'" maxlength="30" placeholder="Vorname" autocomplete="off" type="text">
				</li>
				<li>
					<input name="surname" id="txt_surname" value="'.$row['surname'].'" maxlength="30" placeholder="Nachname" autocomplete="off" type="text">
				</li>
				<li>
					<input name="mobileNumber" id="txt_mobileNumber" value="'.$row['mobileNumber'].'" maxlength="30" placeholder="Telefon" autocomplete="off" type="text">
				</li>
				<li>
					<input name="mail" id="txt_mail" value="'.$row['email'].'" maxlength="30" placeholder="E-Mail" autocomplete="off" type="text">
				</li><li>
					<input name="street" id="txt_street" value="'.$row['street'].'" maxlength="30" placeholder="Strasse" autocomplete="off" type="text">
				</li>
				<li>
					<input name="streetNumber" id="txt_streetNumber" value="'.$row['streetNumber'].'" maxlength="20" placeholder="Hausnummer" autocomplete="off" type="text">
				</li>
				<li>
					<input name="postCode" id="txt_postCode" value="'.$row['postCode'].'" maxlength="10" placeholder="PLZ" autocomplete="off" type="text">
				</li>
				<li>
					<input name="city" id="txt_city" value="'.$row['city'].'" maxlength="30" placeholder="Ort" autocomplete="off" type="text">
					<input type="text" class="fieldOff" name="routeStationDetailsID" id="txt_routeStationDetailsID" value="'.$data['routeStationDetailsID'].'">
					<input type="text" class="fieldOff" name="price" id="txt_price" value="'.$data['price'].'">
					<input type="text" class="fieldOff" name="stationnamefrom" id="txt_stationnamefrom" value="'.$data['stationnamefrom'].'">
					<input type="text" class="fieldOff" name="stationnameto" id="txt_stationnameto" value="'.$data['stationnameto'].'">
					<input type="text" class="fieldOff" name="departure" id="txt_departure" value="'.$data['departure'].'">
					<input type="text" class="fieldOff" name="arrival" id="txt_arrival" value="'.$data['arrival'].'">
					<input type="text" class="fieldOff" name="bookuserid" id="txt_bookuserid" value="'.$data['book_userID'].'">
					<input type="text" class="fieldOff" name="bookid" id="txt_bookid" value="'.$data['ID'].'">
					<input type="text" class="fieldOff" id="flag_edit_booking" name="flag_edit_booking" value="1">
				</li>
		
		';
		
		return $html;
		
	
	}
	
	private function getBookingData($data,$Global) {
	
		$html = '
		
		<div class="left-box">
			<h3>Passagiere</h3>
				<ul>
					<li>
						<label>Erwachsenenzahl:&nbsp;</label>
						<input value="'.$data['adults'].'" name="adultPassengers" id="txt_adults" type="text" readonly>
					</li>
					<li>
						<label>Kinderzahl:&nbsp;</label>
						<input value="'.$data['kids'].'" name="childPassengers" id="txt_kids" type="text" readonly>
					</li>
					<li>
						<label>Gesamtpreis (€):&nbsp;</label>
						<input name="priceTotal" id="txt_priceTotal" value="'.$data['priceTotal'].'" type="text" readonly>
					</li>
				</ul>
				
			</div>
			<div id="passengers">
				<div id="adults">
					<ul><li>Erwachsene:</li></ul>
					'.$this->getAdults($data,$Global).'
					<ul><li><input type="button" value="+1 Erwachsener" id="addOneAdult"></li></ul>
				</div>
				<div id="kids">
					<ul><li>Kinder:</li></ul>
					'.$this->getKids($data,$Global).'
					<ul><li><input type="button" value="+1 Kind" id="addOneKid"></li></ul>
				</div>
			</div>
		<hr>			
		
			<ul id="list_nb_customer">
				<h3>Kundendaten</h3>
				'.$this->getClientData($data,$Global).'
			</ul>
			
			<div id="frm_btns">
				<input id="btn_submit_new_booking" value="BUCHUNG ABSCHLIESSEN" type="submit">
			</div>
		
		';
		
		return $html;
	
	}
	
	
	
	
	public function editBooking($data,$Global) {
	
		$result = array();
		
		$result['html'] = $this->getBookingData($data,$Global);
		$result['freeSeats'] = $this->getFreeSeats($data['routeStationDetailsID']);
		
		return $result;	
	}
	
	private function deletePassengers($data,$Global){
	
		$myPDO = $Global->dbOpen();
		
		$arr['bookingID'] = $data['bookid'];
		
		$sql = 'SELECT count(*) AS passNum from book_passenger WHERE bookingID=:bookingID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		$row = $PDOStatement->fetch();
		
		$sql = 'delete from book_passenger WHERE bookingID=:bookingID';
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		
		return $row['passNum'];
		
			
	}
	
	private function updateBookUser($data,$Global) {
	
		$myPDO = $Global->dbOpen();
		
		$arr['name']=$data['name'];
		$arr['surname']=$data['surname'];
		$arr['sex']=$data['sex'];
		$arr['form']=$data['form'];
		$arr['mobileNumber']=$data['mobileNumber'];
		$arr['email']=$data['mail'];
		$arr['street']=$data['street'];
		$arr['streetNumber']=$data['streetNumber'];
		$arr['city']=$data['city'];
		$arr['postCode']=$data['postCode'];
		$arr['ID']=$data['bookuserid'];
		
		
		$sql = 'UPDATE book_user SET 
		
		name=:name,
		surname=:surname,
		sex=:sex,
		form=:form,
		mobileNumber=:mobileNumber,
		email=:email,
		street=:street,
		streetNumber=:streetNumber,
		city=:city,
		postCode=:postCode
		
		WHERE ID=:ID';


		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
		
	}
	private function updateBookingData($data,$Global) {
	
		$myPDO = $Global->dbOpen();
		
		$arr['adultPassengers'] = $data['adultPassengers'];
		$arr['childPassengers'] = $data['childPassengers'];
		$arr['priceTotal'] = $data['priceTotal'];
		$arr['ID'] = $data['bookid'];
		
		$sql='UPDATE booking SET
		
		adultPassengers=:adultPassengers,
		childPassengers=:childPassengers,
		priceTotal=:priceTotal,
		bookTime = CURRENT_TIMESTAMP
		
		WHERE ID=:ID';
		
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);	
		
	}
	
	public function updateBooking($data,$Global) {
	
		
		$freeSeats = $this->getFreeSeats($data['routeStationDetailsID']);
		$bookedSeats = $data['adultPassengers'] + $data['childPassengers'];
	    $bookID = $data['bookid'];
			
		if ($freeSeats < $bookedSeats) {
			
			$result['status']="nofreeseats";
			$result['freeseats']=$freeSeats;
		
		}
		else {
			
			$this->updateBookingData($data,$Global);
			$seatsRelease = $this->deletePassengers($data,$Global);
			$this->calculateNewFreeSeats($data['routeStationDetailsID'],$seatsRelease,'release'); // release OLD
			
			//insert again the new passengers
			$this->insertPassengers($data,$bookID,$Global);//insert passengers
			$this->calculateNewFreeSeats($data['routeStationDetailsID'],$bookedSeats,'reserve'); // reserve NEW
			
			//update bookUser Data
			$this->updateBookUser($data,$Global);
			
			//sendTicket
			$this->confirmation_ticket_pdf($Global,$bookID);	
			$result['freeseats'] = $this->getFreeSeats($data['routeStationDetailsID']);
			$result['status']="ok";
			$result['routeStationDetailsID']=$data['routeStationDetailsID'];
		
		}
		
		return $result;

	}
	
	
	
	public function makeBooking($data,$Global) {
		
		$result  = array();
		
		$freeSeats = $this->getFreeSeats($data['routeStationDetailsID']);
		$bookedSeats = $data['adultPassengers'] + $data['childPassengers'];
		
		
		if ($freeSeats < $bookedSeats) {
			
			$result['status']="nofreeseats";
			$result['freeseats']=$freeSeats;
			//$result['message']="Jetzt gibt es nur ".$freeSeats." freie Plätze";
			
		}
		else {
			
			$lastBookUserID = $this->insertBookUser($data); // insert into bookuser
			$lastBookID = $this->insertBooking($data,$lastBookUserID,1,1); // insert into booking
			
			$this->insertPassengers($data,$lastBookID,$Global);//insert passengers
			$this->calculateNewFreeSeats($data['routeStationDetailsID'],$bookedSeats,'reserve'); // calculate new free seats and update DB
			$freeSeats = $this->getFreeSeats($data['routeStationDetailsID']);
			
			$this->confirmation_ticket_pdf($Global,$lastBookID);	
			
			$result['freeseats']=$freeSeats;
			$result['status']="ok";
			$result['routeStationDetailsID']=$data['routeStationDetailsID'];

		}
		
		return $result;
		
	}

}

require ('../../php/class_global.php');
require ('../../php/mPDF/mpdf.php');



if (isset($_GET['action'])) {
	
	$B = new __booking();
	$G = new __Global();
	
	
	switch ($_GET['action']) {
        
		case 'prepareBookingForm':
            $B->prepareBookingForm($_POST);
            break;
        
		case 'makeBooking':
            echo json_encode($B->makeBooking($_POST,$G));
            break;
		
		case 'getBookings':
            echo json_encode($B->getBookings($_POST));
            break;
        
		case 'cancelBooking':
            echo json_encode($B->cancelBooking($_POST));
            break;
		
		case 'editBooking':
            echo json_encode($B->editBooking($_POST,$G));
            break;
			
		case 'updateBooking':
            echo json_encode($B->updateBooking($_POST,$G));
            break;
		
		default:
            break;
    }  
} 