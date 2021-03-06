<?php 

class __booking {
	
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
	
	private function clearBasket($Global){
		
		//$Global = new __Global();
		$myPDO = $Global->dbOpen();
		
		$arr['sessionID'] = session_id();
		
		$sql = 'DELETE FROM basket WHERE sessionID = :sessionID '; 
	
		$PDOStatement = $myPDO->prepare($sql);
		$PDOStatement->execute($arr);
		
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
				<img src="../images/_logo_print.png" style="float:left; max-width:180; margin-top:20px;">  
				
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
				<img src="../images/_logo_print.png" style="max-width:200; max-height:100%; margin:0; padding:7px 20px 0 10px;" >
				<img src="../images/stationticket.png" style="float:right; height:40; width:40; margin:12px 15px 10px 0;" >		
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
	
	
	
	private function confirmation_ticket_html($Global,$bookingID){
	
	$html_1='<html>	
	<head>
    	<title>Online-Ticket Flowerpower i Bus GmbH</title>
    	<meta name="description" content="website description">
    	<meta name="keywords" content="website keywords, website keywords">
    	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    	<link rel="stylesheet" type="text/css" href="../css/print.css">
	</head>';
		
	$html_2 = $this->generate_ticket($Global,$bookingID);
	$html_3 = '</html>';
	
	$html = $html_1.$html_2.$html_3;
	
		$myfile = fopen('../tickets/Buchung_'.$bookingID.'.html', 'w') or die('Unable to open file!');
		fwrite($myfile, $html);
		fclose($myfile);
	}
	
	
	
	private function confirmation_ticket_pdf($Global,$bookingID){
		
		$ticket = $this->generate_ticket($Global,$bookingID);
		
		$html = $ticket['html'];
		$Buchungsnummer = $ticket['Buchungsnummer'];
		
		unset($mpdf);

		$mpdf = new mPDF('utf-8', array(210,297));
		$mpdf->WriteHTML($html);
		
		$file_to_create = '../'.$Global->folder_pdf('ticket').'/Buchungsbestaetigung_'.$Buchungsnummer.'.pdf';
		
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
	
	
	private function insertPassenger($name,$surname,$type,$bookingID,$price,$mobileNumber,$Global){
		
		//$Global = new __Global();
		$myPDO = $Global->dbOpen();
		
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
	
	private function insertPassengers($data,$lastBookID,$price,$Global){
		
		//print_r ($data);
		
		
		$adultPassengers = $data['adultPassengers'];
		$childPassengers = $data['childPassengers'];
		$adultprice = $Global->price('adult',$price);
		
		//$Global = new __Global();	
		$childprice = $Global->price('child',$price);
		
		
		///insert adults
		for ($i=0;$i<$adultPassengers;$i++) {
			$this->insertPassenger($data['adultName'][$i],$data['adultSurname'][$i],1,$lastBookID,$adultprice,$data['adultTel'][$i],$Global);
		}
		///insert kids
		if ($childPassengers > 0) {
		
			for ($i=0;$i<$childPassengers;$i++) {
				$this->insertPassenger($data['kidName'][$i],$data['kidSurname'][$i],2,$lastBookID,$childprice,'',$Global);
			}
		
		
		}
			
	}
	private function insertBooking($data,$lastBookUserID,$bookStatus,$bookAdmin,$Global){
	
		//$Global = new __Global();
		$myPDO = $Global->dbOpen();
		
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
	
	private function insertBookUser($data,$Global){
		
		$myPDO = $Global->dbOpen();
		
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

	
	private function makeBooking($data,$lastBookUserID,$Global) {
	
	
		$bookedSeats = $data['adultPassengers'] + $data['childPassengers'];
		
		$lastBookID = $this->insertBooking($data,$lastBookUserID,1,2,$Global); // insert into booking
		$price = $data['price'];
		$this->insertPassengers($data,$lastBookID,$price,$Global);//insert passengers
		//$this->confirmation_ticket_html($Global,$lastBookID);
		//$this->confirmation_ticket_pdf($Global,$lastBookID);	
		
		return $lastBookID;
	}
	
	

	public function makeBookings($data,$Global){
	
		
		$lastBookUserID = $this->insertBookUser($data,$Global); //ok
		$result['data'] = $data;
		
		$result['count'] = count($data['basketID']);
		
		//for ($i=0;$i<count($data['basketID']);$i++) {
		for ($i=0;$i<count($data['basketID']);$i++) {
			
			$basketID = $data['basketID'][$i];
			$newData['routeStationDetailsID'] =  $data['routeStationDetailsID_'.$basketID];
			
			$newData['adultPassengers'] =  $data['adultPassengers_'.$basketID];
			$newData['childPassengers'] =  $data['childPassengers_'.$basketID];
			$newData['adultName'] =  $data['adultName_'.$basketID];
			$newData['adultSurname'] =  $data['adultSurname_'.$basketID];
			$newData['adultTel'] =  $data['adultTel_'.$basketID];
			
			if ($newData['childPassengers'] > 0) {
				$newData['kidName'] =  $data['kidName_'.$basketID];
				$newData['kidSurname'] =  $data['kidSurname_'.$basketID];
			
			}
			
			$newData['price'] =  $data['price_'.$basketID];
			$newData['priceTotal'] = $data['priceTotal_'.$basketID];
			
			$lastBookId = $this->makeBooking($newData,$lastBookUserID,$Global);
			
			
			$result['bookId'][$i] = $lastBookId;
			
		}
		$result['status'] = 'ok';
		
		$this->clearBasket($Global);
		
		return $result;
	
	}


}


?>