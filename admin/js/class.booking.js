
function __booking() {

	var __this = this;
	
	var _RIDE_OBJ = '';
	
	this.hide = function(){
	
		$('#bookingTableHeader').hide();
		$('#bookingTable').hide();
	
	}
	
	this.checkNewbookingForm = function(){
		
		//return true;
		
		function validateEmail(email) { 
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		} 
				
		function validatePassengerNames(className) {
			var name = $(className);
			for (var i=0;i<name.length;i++) {
				if ($.trim(name[i].value) === '') {
					//alert('Erwachsene Name bitte!!');
					name[i].focus();
					return false;
				}
			}
			return true;
		}
		
		
		function validatePassengerTel(className){
		
			var tel = $(className);
			for (var i=0;i<tel.length;i++) {
				
				var val = $.trim(tel[i].value);
				
				if (val != parseInt(val,10)) {
				
					tel[i].focus();
					return false;
				}
			}
			return true;
		
		}
		
		if (validatePassengerNames('.adultName') ===false) {
			alert('Bitte geben Sie den/die Vornamen des/der erwachsenen Passagier(e) an.');
			return false;
		}
		
		if (validatePassengerNames('.adultSurname') ===false) {
			alert('Bitte geben Sie den/die Nachnamen des/der erwachsenen Passagier(e) an.');
			return false;
		}
		
		if (validatePassengerTel('.passMobileNumber') ===false) {
			alert('Bitte geben Sie eine gültige Telefonnummer an. Diese muss numerisch sein.');
			return false;
		}
		
		
		if (validatePassengerNames('.kidName') ===false) {
			alert('Bitte geben Sie den/die Vornamen des/der jungen Passagier(e) an.');
			return false;
		}
		
		if (validatePassengerNames('.kidSurname') ===false) {
			alert('Bitte geben Sie den/die Nachnamen des/der jungen Passagier(e) an.');
			return false;
		}
		
		if ( $('#txt_adults').val() === '0' ) {
		
			alert("Kinder dürfen nicht allein ohne erwachsene Begleitperson reisen.");
			$('#txt_adults').focus();
			return false;
		}
		
		if ( $.trim($('#txt_name').val()) === '' ) {
		
			alert("Bitte geben Sie den Vornamen des Buchungskunden an.");
			$('#txt_name').focus();
			return false;
		}
		if ( $.trim($('#txt_surname').val()) === '' ) {
		
			alert("Bitte geben Sie den Nachnamen des Buchungskunden an.");
			$('#txt_surname').focus();
			return false;
		}
		
		var mobilenumber = $('#txt_mobileNumber').val();
		if (mobilenumber != parseInt(mobilenumber,10)) {
			
			alert("Bitte geben Sie eine gültige Telefonnummer an. Diese muss numerisch sein.");
			$('#txt_mobileNumber').focus();
			return false;
		}
		if (validateEmail($('#txt_mail').val()) === false) {
			alert('Bitte geben Sie eine gültige E-Mail-Adresse an.');
			$('#txt_mail').focus();
			return false;
		}
		
		if ( $.trim($('#txt_street').val()) === '' ) {
		
			alert("Bitte geben Sie einen gültigen Strassennamen an.");
			$('#txt_street').focus();
			return false;
		}
		if ( $.trim($('#txt_streetNumber').val()) === '' ) {
		
			alert("Bitte geben Sie eine gültige Hausnummer an.");
			$('#txt_streetNumber').focus();
			return false;
		}
		
		if ( $.trim($('#txt_postCode').val()) === '' ) {
		
			alert("Bitte geben Sie eine gültige Postleitzahl an.");
			$('#txt_postCode').focus();
			return false;
		}
		
		if ( $.trim($('#txt_city').val()) === '' ) {
		
			alert("Bitte geben Sie den Wohnort des Buchungskunden an.");
			$('#txt_city').focus();
			return false;
		}
		
		return true;
	
	}
	
	this.initBookForm = function(){
		
		$('#txt_adults').val('0');
		$('#txt_kids').val('0');
		$('#txt_priceTotal').val('0');
		$('#adults').html('');
		$('#kids').html('');
		
		$('#txt_name').val('');
		$('#txt_surname').val('');
		$('#txt_mobileNumber').val('');
		$('#txt_mail').val('');
		$('#txt_street').val('');
		$('#txt_streetNumber').val('');
		$('#txt_postCode').val('');
		$('#txt_city').val('');
		$('#btn_submit_new_booking').attr("disabled", false);
	}
	
	this.loadEvents = function(){
		
		
		$('#newBookHeader').click(function(){
			
			$('#newBook').slideToggle( "slow" );
			
		});
		$('#bookingsHeader').click(function(){
			
			$('#bookings').slideToggle( "slow" );
			
		});
		
		$('#txt_adults,#txt_kids').keyup(function(){
			__this.check_numPassengers($(this),$('#lbl_freeseats').attr('data-freeseats'));
		});
		
		$('#btn_cancel_new_booking').click(function(){
			__this.initBookForm();
		})
		
		$('#frm_newbooking').submit(function(e) { //Formular wird abgesendet
			e.preventDefault(); //Aktion submit aufgehalten...
			
			if (__this.checkNewbookingForm() === true) {
				
				$data = $(this).serialize();
				//alert($data);
				//return;
				if ($('#flag_edit_booking').length === 0) {
					//INSERT BOOKING
					
						$.post('php/booking.php?action=makeBooking', $data , function(result) {
							$('#btn_submit_new_booking').attr("disabled", true);
							$('#lbl_freeseats').html(result.freeseats);
							$('#lbl_freeseats').attr('data-freeseats',result.freeseats);
							
							if (result.status==='nofreeseats'){
								alert('Jetzt gibt es auf dieser Fahrt noch '+result.freeseats+' freie Plätze.');
							}
							else {
								alert('Ihre Buchung wurde erfolgreich abgeschlossen.');
								//$('#testArea').html(result.mailHTML);
								__this.initBookForm();
								__this.getBookings(result.routeStationDetailsID);
								
								myRoute.getRouteList();
								myTimeTable.getTimeTableList();
								myPassengerList.initResults();
								
								
								$('#newBook').hide( "slow" );
								$('#bookings').show( "slow" );
								
							}
						
						},'json');
				
				}
				else {
				
				//UPDATE BOOKING
				//alert('UPDATE'); 
				
					$.post('php/booking.php?action=updateBooking', $data , function(result) {
							
							$('#btn_submit_new_booking').attr("disabled", true);
							$('#lbl_freeseats').html(result.freeseats);
							$('#lbl_freeseats').attr('data-freeseats',result.freeseats);
							
							if (result.status==='nofreeseats'){
								alert('Jetzt gibt es auf dieser Fahrt noch '+result.freeseats+' freie Plätze.');
							}
							else {
							
								alert('Ihre Buchung wurde erfolgreich aktualisiert.');
								//$('#btn_submit_new_booking').attr("disabled", false);
								myRoute.getRouteList();
								myTimeTable.getTimeTableList();
								myPassengerList.initResults();
								__this.prepareBooking(_RIDE_OBJ,"#bookings");
								
							
							}
						
					},'json');
					
				
				}
				 
				 
			}
			
		});
		
		
	}
	
	
	this.check_numPassengers=function(obj,freeSeats) {
	
			var adultsDiv = document.getElementById('adults');
			var kidsDiv = document.getElementById('kids');
			
			var adults = +document.getElementById('txt_adults').value;
			var kids = +document.getElementById('txt_kids').value;
			var price = +document.getElementById('txt_price').value;
			var priceTotalObj = document.getElementById('txt_priceTotal');
			
			if (adults !== parseInt(adults,10)) {adults=0;document.getElementById('txt_adults').value=0;}
			if (kids !== parseInt(kids,10)) {kids=0;document.getElementById('txt_kids').value=0;}

			var sum = adults + kids;
			//var priceTotal = (adults*price) + kids*(price/2);
			var priceTotal = parseInt((adults*getPrice('adult',price)) + (kids*getPrice('child',price)),10);
			priceTotalObj.value = priceTotal;
			
			//console.log("---------kids="+kids);
			//console.log("---------adults="+adults);
			//console.log("---------price="+price);
			
			if (sum>freeSeats){
				alert('Für diese Fahrt sind nicht mehr genug freie Plätze verfügbar. Sie können noch maximal '+freeSeats+' Plätze buchen.');
				document.getElementById('txt_adults').value = "0";
				document.getElementById('txt_kids').value=0;
				priceTotalObj.value = 0;
				adultsDiv.innerHTML = '';
				kidsDiv.innerHTML = '';
				return;
			};
			adultsDiv.innerHTML = '';
			if (adults>0){
				adultsStr='<ul><li>Erwachsene:</li></ul>';
				for (var i=1;i<=adults;i++){
					adultsStr+='<ul>';
						adultsStr+='<li><input class="adultName" name="adultName[]" id="adultName_'+i+'" type="text" value="" maxlength="30" placeholder="Vorname" autocomplete="off"><input name="adultSurname[]" maxlength="30" class="adultSurname" id="adultSurname_'+i+'" type="text" value="" placeholder="Nachname" autocomplete="off"><input name="passMobileNumber[]" maxlength="30" class="passMobileNumber" id="passMobileNumber_'+i+'" type="text" value="" placeholder="Mobilnummer" autocomplete="off"></li>';
					adultsStr+='</ul>';
				}
				adultsDiv.innerHTML = adultsStr;
			}
			
			////////////////////////////////////////
			kidsDiv.innerHTML = '';
			if (kids>0){
				kidsStr='<ul><li>Kinder:</li></ul>';
				for (var i=1;i<=kids;i++){
					kidsStr+='<ul>';
						kidsStr+='<li><input class="kidName" name="kidName[]" id="kidName_'+i+'" type="text" value="" maxlength="30" placeholder="Vorname" autocomplete="off"><input name="kidSurname[]" maxlength="30" class="kidSurname" id="kidSurname_'+i+'" type="text" value="" placeholder="Nachname" autocomplete="off"></li>';
					kidsStr+='</ul>';
				}
				kidsDiv.innerHTML = kidsStr;
			}
			
		}
	
	this.prepareBooking = function(obj){
		
		var $obj = $(obj);
		
		_RIDE_OBJ = obj;
		
		var _arguments = arguments;
		
		var stationnamefrom = obj.getAttribute('data-stationnamefrom');
		var stationnameto = obj.getAttribute('data-stationnameto');
		var arrival = obj.getAttribute('data-arrival');
		var departure = obj.getAttribute('data-departure');
		
		var price = obj.getAttribute('data-price');
		var busseats = obj.getAttribute('data-busseats');
		var busmodel = obj.getAttribute('data-busmodel');
		var buscode = obj.getAttribute('data-buscode');
		var freeseats = obj.getAttribute('data-freeseats');
		var routeStationDetailsID = obj.getAttribute('data-id');
		var activeride = obj.getAttribute('data-activeride');
		
		var ajaxCon = myAjax.connect();
		ajaxCon.open("POST","php/booking.php?action=prepareBookingForm",true);
		ajaxCon.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajaxCon.onreadystatechange = function(){
		
			if (ajaxCon.readyState == 4 && ajaxCon.status == 200) {
				//LB.open(ajaxCon.responseText);	
				
				
				$('#bookingTableHeader').show('slow');
				$('#bookingTable').html(ajaxCon.responseText);
				$('#td_freeSeats_'+routeStationDetailsID).html($('#lbl_freeseats').html());
				$('#timeTable').hide('slow');
				$('#bookingTable').show('slow');
				$('#newBookHeader2').hide();
				
				
				//$('#bookingTable').removeClass('PastRide');
				//$('#bookingTable').removeClass('cancelled');
				
				//if ($obj.hasClass('cancelled')) {
				//	$('#bookingTable').addClass('cancelled');
				//}
				
				//if ($obj.hasClass('PastRide')) {
					//$('#bookingTable').addClass('PastRide');
				//}
				
				if (_arguments.length>1) {
					
					
					$(_arguments[1]).show();
					
					
				}
				
				
				//$('#newBookHeader').css("width","140px"); 
				
				
				__this.loadEvents();
				__this.getBookings(routeStationDetailsID);
			}
		};
		
		ajaxCon.send("stationnamefrom="+stationnamefrom+"&stationnameto="+stationnameto+"&arrival="+arrival+"&departure="+departure+"&price="
					+price+"&busseats="+busseats+"&busmodel="+busmodel+"&buscode="+buscode+"&freeseats="+freeseats+"&routeStationDetailsID="+routeStationDetailsID+"&activeride="+activeride);
		
	}
	
	function getBookingNumber(bookDate,bookID) {
	
		var result = bookDate.replace("-", ""); 
		result = result.replace("-", ""); 
		result = '#'+result+'/0'+bookID;
		
		return result;
	
	
	}
	
	function cancelBooking($obj){
	
		var $data='ID='+$obj.attr('data-id')+'&book_userID='+$obj.attr('data-bookuserid')+'&passengers='+$obj.attr('data-passengers')+'&routeStationDetailsID='+$obj.attr('data-rsdid');
		
		//alert($data);
		
		$.post('php/booking.php?action=cancelBooking', $data , function(result) {
			
			$('#lbl_freeseats').attr('data-freeseats',result.freeSeats);
			$('#lbl_freeseats').html(result.freeSeats);
			
			__this.getBookings($obj.attr('data-rsdid'));
			__this.prepareBooking(_RIDE_OBJ,"#bookings");
			
			myRoute.getRouteList();
			myTimeTable.getTimeTableList();
			myPassengerList.initResults();
			
			alert(result.msg);
		},'json');

	
	}
	
	
	
	function cancelBookings_event(){
	
	
		$('.btn_cancelBooking').each(function() {
			
			$(this).click(function(){
				var msg = confirm('Sind Sie sicher daß Sie diese Buchung stornieren wollen?');
				if (msg === true) {
					cancelBooking($(this));
				}
				
			})
		});
		
	}
	
	function loadEvents_delete(){
	
		$('.passDelete').each(function() {
			
			$(this).click(function(){
				
				$(this).parent().parent().remove();
				calc_totalPrice_passengersNum();
				
			})
			
			
		});
	
	}
	
	
	function calc_totalPrice_passengersNum(){
	
	
		$('#txt_adults').val($('.adultName').length);
		$('#txt_kids').val($('.kidName').length);
		
		var adults = $('#txt_adults').val();
		var kids = $('#txt_kids').val();
		var price = $('#book_price').attr('data-bookprice');
		
		var priceTotal = parseInt((adults*getPrice('adult',price)) + (kids*getPrice('child',price)),10);
		
		//alert(passengersNum);
		
		$('#txt_priceTotal').val(priceTotal);
	
	
	}
	

	
	function calc_insert_passenger(html,$obj) {
	
		
			var passengersNum = parseInt($('.adultName').length,10) + parseInt($('.kidName').length,10);
			var freeSeats = $('#lbl_freeseats').attr('data-freeseats');
			
			if (passengersNum+1 > freeSeats) {
			
				alert('Für diese Fahrt sind nicht mehr genug freie Plätze verfügbar. Sie können noch maximal '+freeSeats+' Plätze buchen.');
				return;
			
			}
			$(html).insertBefore($obj.parent().parent());
			
			calc_totalPrice_passengersNum();
	
	}
	
	function loadEvents_Edit(){
	
	
		$('#addOneAdult').click(function(){
			
				var html = '<ul><li>';
				html+='<input class="adultName" name="adultName[]"  value="" maxlength="30" placeholder="Vorname" autocomplete="off" type="text">';
				html+='<input name="adultSurname[]" maxlength="30" class="adultSurname"  value="" placeholder="Nachname" autocomplete="off" type="text">';
				html+='<input name="passMobileNumber[]" maxlength="30" class="passMobileNumber"  value="" placeholder="Mobilnummer" autocomplete="off" type="text">';
				html+='<input type="button" value="löschen" class = "passDelete">';
				html+='</li></ul>';	
			
			calc_insert_passenger(html,$(this));
			
			loadEvents_delete();
		
		})
		
		
		$('#addOneKid').click(function(){
				
				
				var html = '<ul><li>';
				html+='<input class="kidName" name="kidName[]"  value="" maxlength="30" placeholder="Vorname" autocomplete="off" type="text">';
				html+='<input name="kidSurname[]" maxlength="30" class="kidSurname" value="" placeholder="Nachname" autocomplete="off" type="text">';
				html+='<input type="button" value="löschen" class = "passDelete">';
			    html+='</li></ul>';
			
			calc_insert_passenger(html,$(this));
			
			loadEvents_delete();
			
		})
		
	//	alert($('.passDelete').length);
		
	}
	
	
	function editBooking($obj){
		
		
		var $data='stationnamefrom='+$obj.attr('data-stationnamefrom')+'&stationnameto='+$obj.attr('data-stationnameto')+'&departure='+$obj.attr('data-departure')+'&arrival='+$obj.attr('data-arrival')+'&price='+$obj.attr('data-price')+'&ID='+$obj.attr('data-id')+'&book_userID='+$obj.attr('data-bookuserid')+'&adults='+$obj.attr('data-adults')+'&kids='+$obj.attr('data-kids')+'&routeStationDetailsID='+$obj.attr('data-rsdid')+'&priceTotal='+$obj.attr('data-pricetotal');
		
		$.post('php/booking.php?action=editBooking', $data , function(result) {
			
			$('#lbl_freeseats').attr('data-freeseats',result.freeSeats);
			$('#lbl_freeseats').html(result.freeSeats);
			myRoute.getRouteList();
			myTimeTable.getTimeTableList();
			
			
			$('#frm_newbooking').html(result.html);
			$('#newBookHeader').html('BUCHUNG BEARBEITEN');
			$('#newBookHeader').css("width","200px"); 
			$('#bookings').hide('slow');
			//$('#bookingsHeader').hide();
			$('#newBook').show('slow');
			
			$('#newBookHeader2').show();
			$('#newBookHeader2').click(function(){
				__this.prepareBooking(_RIDE_OBJ,"#newBook");
				
			})
			
			loadEvents_Edit();
			loadEvents_delete();
			
		},'json');
	
	}
	
	
	function editBookings_event(){
		
		
		$('.btn_editBooking').each(function() {
			
			$(this).click(function(){
				editBooking($(this));
			})
		});
		
	}
	
	
	function getPassengersNum(adults,children){
	
		var result = parseInt(parseInt(adults,10)+parseInt(children,10),10);
		return result;
	
	}
	
	this.getBookings = function(routeStationDetailsID){
		
		//alert('routeStationDetailsID='+routeStationDetailsID);
		var $data = "routeStationDetailsID="+routeStationDetailsID;
		
		$.post('php/booking.php?action=getBookings', $data , function(result) {
			
			//alert(result.freeSeats);
			//alert(result.rows[0].mobileNumber);
			
			$('#lbl_freeseats').html(result.freeseats);
			$('#lbl_freeseats').attr('data-freeseats',result.freeseats);
			
		
		var html = '';
		
		var status = ["reserviert", "gebucht", "storniert"];
		var bookFrom = ["backend", "frontend"];
		
		html += '<table class="bookingsTable">';
		html += '<thead><tr>';
			html += '<th><b>Buchungskunde</b></th>';
			html += '<th><b>Buchungsnr.&nbsp;</b></th>';
			html += '<th><b>Telefon</b></th>';
			html += '<th><b>E-Mail</b></th>';
			html += '<th><b>Ort</b></th>';
			html += '<th><b>Adresse</b></th>';
			html += '<th><b>Buchungszeit</b></th>';
			html += '<th><b>Plätze</b></th>';
			html += '<th><b>Preis</b></th>';
			html += '<th><b>Status</b></th>';
			html += '<th><b>System</b></th>';
			html += '<th><b>Aktion</b></th>';
			
		html += '</tr></thead>';	
		
		var stationnamefrom = $('#book_stationNameFrom').val();
		var stationnameto = $('#book_stationNameTo').val();
		var departure = $('#book_departure').val();
		var arrival = $('#book_arrival').val();
		var price = $('#book_price').attr('data-bookprice');
		
		
		
		for (var i=0;i<result.rows.length;i++) {
			var disable_str='';
			var cancel_val='Stornieren';
			
			if (parseInt(result.rows[i].bookStatus,10) === 3) {
				disable_str=' disabled ';
				cancel_val='storniert';
				edit_btn='';
			}
			
			
			var edit_btn = '<input type="button" '+disable_str+' id="btn_editBooking_'+result.rows[i].ID+'" data-price="'+price+'" data-arrival="'+arrival+'" data-departure="'+departure+'" data-stationnameto="'+stationnameto+'" data-stationnamefrom="'+stationnamefrom+'" data-pricetotal="'+result.rows[i].priceTotal+'" data-id="'+result.rows[i].ID+'" data-rsdid="'+result.rows[i].routeStationDetailsID+'" data-bookuserid="'+result.rows[i].book_userID+'" data-adults="'+result.rows[i].adultPassengers+'" data-kids="'+result.rows[i].childPassengers+'" class="btn_editBooking" value="Bearbeiten">';
			
		
			html += '<tr>';
			
				html += '<td>'+result.rows[i].name+' '+result.rows[i].surname+'</td>';
				html += '<td>'+getBookingNumber(result.rows[i].dateBookTime,result.rows[i].ID)+'</td>';
				html += '<td>'+result.rows[i].mobileNumber+'</td>';
				html += '<td>'+result.rows[i].email+'</td>';
				html += '<td>'+result.rows[i].postCode+' '+result.rows[i].city+'</td>';
				html += '<td>'+result.rows[i].street+' '+result.rows[i].streetNumber+'</td>';
				html += '<td>'+result.rows[i].bookTime+'</td>';
				html += '<td>'+getPassengersNum(result.rows[i].adultPassengers,result.rows[i].childPassengers)+'</td>';
				html += '<td>'+result.rows[i].priceTotal+' €'+'</td>';
				html += '<td>'+status[result.rows[i].bookStatus-1]+'</td>';
				html += '<td>'+bookFrom[result.rows[i].bookAdmin-1]+'</td>';
				html += '<td>';
					html += edit_btn+'</br>';
					html += '<input type="button" '+disable_str+' id="btn_cancelBooking_'+result.rows[i].ID+'" data-id="'+result.rows[i].ID+'" data-rsdid="'+result.rows[i].routeStationDetailsID+'" data-bookuserid="'+result.rows[i].book_userID+'" data-passengers="'+getPassengersNum(result.rows[i].adultPassengers,result.rows[i].childPassengers)+'" class="btn_cancelBooking" value="'+cancel_val+'">';
				html += '</td>';
			html += '</tr>';
		}
		
		html += '</table>';
		
		if (result.rows.length===0){
			
			html='<p id="nobookinfo">Noch keine Buchungen für diese Fahrt vorhanden.</p>';
			
		}
		
		$('#bookings').html(html);
		
		
		if ($('#newBookHeader').length>0) { //active Ride
			cancelBookings_event();
			editBookings_event();
		}
		else {
			$('.btn_cancelBooking').prop( "disabled", true );// its not active ride, disable stornieren
			$('.btn_editBooking').prop( "disabled", true );// its not active ride, disable stornieren
		}
		
		
		
		},'json');
	
	}
		
}