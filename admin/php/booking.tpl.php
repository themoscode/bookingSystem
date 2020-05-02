<div id="booking">
	
	<h3>BUCHUNGEN</h3>
	<div id="bookstatData">
		<div>
			<ul class="left-list">
				<li>
					<label id="label-from" >Von:&nbsp;</label>
					<input type="text" readonly name="" class="" method="" value="<?php echo $data['stationnamefrom'];?>" 										id="book_stationNameFrom">
				</li>
				<li>
					<label id="label-to">Nach:</label>
					<input type="text" readonly name="" class="" method="" value="<?php echo $data['stationnameto'];?>" 										id="book_stationNameTo">
				</li>
			</ul>
		</div>
		<div>
			<ul class="right-list">
				<li>
					<label id="label-bus">Businfo:&nbsp;</label>
					<input type="text" readonly name="" class="" method="" value="<?php echo $data['busmodel'];?>&nbsp;<?php echo 								$data['busseats'];?>&nbsp;Sitzer" id="book_bus">					
				</li>
				<li>
					<label>Preisinfo:</label>
					<input type="text" readonly name="" class="" method="" data-bookprice="<?php echo $data['price'];?>" 										id="book_price" value="<?php echo $data['price'];?>€" >	
				</li>
			</ul>
		</div>
		<div>
			<ul class="center-list">
				<li>
					<label id="label-depart">Abfahrt:</label>
					<input type="text" readonly name="" class="" method="" value="<?php echo $data['departure'];?>" 											id="book_departure">								
				</li>
				<li>
					<label>Ankunft:</label>
					<input type="text" readonly name="" class="" method="" value="<?php echo $data['arrival'];?>" 												id="book_arrival">
				</li>						
			</ul>
		</div>
	</div>
	
	<div>
		<ul id="freeSeatlist">
			<li>
				<label>Freie Plätze:</label>
				<label id="lbl_freeseats" data-freeseats="<?php echo $this->getFreeSeats($data['routeStationDetailsID']);?>"><?php echo $this->getFreeSeats($data['routeStationDetailsID']);?></label>
			</li>
		</ul>
	</div>
	
	<div id="bookingsHeader">BUCHUNGEN</div>
	<div id="bookings" class="fieldOff"></div>
	
	<?php 
	if ($data['activeride']!="") {
	?>
	<div id="newBookHeader">NEUE BUCHUNG</div>
	<div id="newBookHeader2" class="fieldOff">NEUE BUCHUNG</div>
	<div id="newBook" class="fieldOff">
				

	<form id="frm_newbooking" >
		
			<div class="left-box">
			<h3>Passagiere</h3>
				<ul>
					<li>
						<label>Erwachsenenzahl:&nbsp;</label>
						<input type="text" value="0"  name="adultPassengers" id="txt_adults"  autocomplete="off">
					</li>
					<li>
						<label>Kinderzahl:&nbsp;</label>
						<input type="text" value="0"  name="childPassengers" id="txt_kids"  autocomplete="off">
					</li>
					<li>
						<label>Gesamtpreis (€):&nbsp;</label>
						<input type="text" name="priceTotal" id="txt_priceTotal" value="0" readonly>
					</li>
				</ul>
				
			</div>
			<div id="passengers">
				<div id="adults"></div>
				<div id="kids"></div>
			</div>
		
		<hr>			
		
			<ul id="list_nb_customer">
				<h3>Kundendaten</h3>
				<li>
					<input type="radio" name="sex" value="Frau" id="txt_frau" class="input_radio" checked><span>Frau</span>
					<input type="radio" name="sex" value="Herr" id="txt_herr" class="input_radio"><span>Herr</span>	
				</li>
				<li>
					<input type="radio" name="form" value="Person" id="txt_priv" class="input_radio" checked><span>Person</span>
					<input type="radio" name="form" value="Firma" id="txt_comp" class="input_radio"><span>Firma</span>				
				</li>
				<br>
				<li>
					<input type="text" name="name" id="txt_name" value="" maxlength="30" placeholder="Vorname" autocomplete="off">
				</li>
				<li>
					<input type="text" name="surname" id="txt_surname" value="" maxlength="30" placeholder="Nachname" autocomplete="off">
				</li>
				<li>
					<input type="text" name="mobileNumber" id="txt_mobileNumber" value="" maxlength="30" placeholder="Telefon" autocomplete="off">
				</li>
				<li>
					<input type="text" name="mail" id="txt_mail" value="" maxlength="30" placeholder="E-Mail" autocomplete="off">
				<li>
					<input type="text" name="street" id="txt_street" value="" maxlength="30" placeholder="Strasse" autocomplete="off">
				</li>
				<li>
					<input type="text" name="streetNumber" id="txt_streetNumber" value="" maxlength="20" placeholder="Hausnummer" autocomplete="off">
				</li>
				<li>
					<input type="text" name="postCode" id="txt_postCode" value="" maxlength="10" placeholder="PLZ" autocomplete="off">
				</li>
				<li>
					<input type="text" name="city" id="txt_city" value="" maxlength="30" placeholder="Ort" autocomplete="off">
					<input type="text" class="fieldOff" name="routeStationDetailsID" id="txt_routeStationDetailsID" value="<?php echo $data['routeStationDetailsID'];?>">
					<input type="text" class="fieldOff" name="price" id="txt_price" value="<?php echo $data['price'];?>">
					<input type="text" class="fieldOff" name="stationnamefrom" id="txt_stationnamefrom" value="<?php echo $data['stationnamefrom'];?>">
					<input type="text" class="fieldOff" name="stationnameto" id="txt_stationnameto" value="<?php echo $data['stationnameto'];?>">
					<input type="text" class="fieldOff" name="departure" id="txt_departure" value="<?php echo $data['departure'];?>">
					<input type="text" class="fieldOff" name="arrival" id="txt_arrival" value="<?php echo $data['arrival'];?>">
					
				</li>
			</ul>
			
			<div id="frm_btns">
				<input type="submit" id="btn_submit_new_booking" value="BUCHUNG ABSCHLIESSEN" >
				<input type="button" id="btn_cancel_new_booking" name="cancel" value="ABBRECHEN">
			</div>
		
		</form>
	</div><!-- end booking -->
	<?php 
	}
	?>
</div>