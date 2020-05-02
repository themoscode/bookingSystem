function __booking(){
	
	var __this = this;
	
	this.hide = function($how){
		
		$('#bookingClients').hide($how);
	
	}
	
	this.show = function($how){
	
		$('#bookingClients').show($how);
		
	}
	
	function ooobooking_msg(result){
		
		var str='Vielen Dank für Ihre Buchungen!<br>';
		str+='In Kürze erhalten Sie eine E-Mail mit Ihrer Buchungsbestätigung.';
		var path = 'tickets/Buchung_';
		var bookID = result.bookId;
		
		/*
		for (var i=0;i<bookID.length;i++) {
		
			str+='Buchung Nr:'+bookID[i]+' <a href="'+path+bookID[i]+'.html" target="_blank">HTML</a>,<a href="'+path+bookID[i]+'.pdf" target="_blank">PDF</a><br>';
		
		}
		*/
		return str;	
	
	}

	function booking_msg(result){
		
		var str='Vielen Dank für Ihre Buchungen!<br>';
		str+='In Kürze erhalten Sie eine E-Mail mit Ihrer Buchungsbestätigung.<br><br><hr>';
		
		str+='Buchung Nr:000005 <a href="tickets/Buchung_5.pdf" target="_blank">PDF</a><br>';

		return str;	
	
	}



	
	function preload_makeBooking($data){
	
		$('#booking').html('<img src="images/preloader.png" border="0" class="preloader">');
		$('#booking').show();
		setTimeout(function(){makeBooking($data);}, 500);
	
	}
	
	
	function makeBooking($data){
	
			$.post('php/booking.php?action=makeBookings', $data , function(result) {
					
					//console.log (result);
					$('#booking').hide();
					engine.msg_main(booking_msg(result));
					basket.show();
					//slideshow.show();
					
					
			},'json');
	
	}
	
	
	function __checkNewbookingForm(){
		
		
		var __this = this;
		this.valid = true;
		$('.form_msg').hide();
		
		
		function validateEmail(email) { 
			//var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			var re = /^\s*[^@!"¤$%&\/()=\[\]\\«`*+#',;<>|{}\s]+@[^@!"¤$%&\/()=\[\]\\«`*+#',;<>|{}_\s]{2,}[.][a-z]{2,}\s*$/;
			
			return re.test(email);
		} 
				
		
		function validatePassengerNames(className) {
			
			//var name = $(className);
			$(className).each(function(){
				
				if ($(this).val() === "" ){
				
					 //$(this).focus();
					 $(this).parent('div').children('label').children('span').show();
					 __this.valid = false;
				}
			
			})
			
		}
		
		function validatePassengersTel(className) {
			
			//var name = $(className);
			$(className).each(function(){
				
				if ($(this).val() != parseInt($(this).val()) ){
				
					// $(this).focus();
					 $(this).parent('div').children('label').children('span').show();
					 __this.valid = false;
				}
			
			})
			
		}
		
		validatePassengerNames('.names');
		validatePassengersTel('.adultTel');
		
		if ( $.trim($('#txt_name').val()) === '' ) {
		
			$('#txt_name').parent('div').children('label').children('span').show();
			//$('#txt_name').focus();
			__this.valid = false;
		}
		
		if ( $.trim($('#txt_surname').val()) === '' ) {
		
			$('#txt_surname').parent('div').children('label').children('span').show();
			//$('#txt_surname').focus();
			__this.valid = false;
		}
		
		var mobilenumber = $('#txt_mobileNumber').val();
		if (mobilenumber != parseInt(mobilenumber)) {
			
			$('#txt_mobileNumber').parent('div').children('label').children('span').show();
			//$('#txt_mobileNumber').focus();
			__this.valid = false;
		}
		
		if (validateEmail($('#txt_mail').val()) === false) {
			$('#txt_mail').parent('div').children('label').children('span').show();
			//$('#txt_mail').focus();
			__this.valid = false;
		}
		
		if ( $.trim($('#txt_street').val()) === '' ) {
		
			$('#txt_street').parent('div').children('label').children('span').show();
			//$('#txt_street').focus();
			__this.valid = false;
		}
		if ( $.trim($('#txt_streetNumber').val()) === '' ) {
		
			$('#txt_streetNumber').parent('div').children('label').children('span').show();
			//$('#txt_streetNumber').focus();
			__this.valid = false;
		}
		
		if ( $.trim($('#txt_postCode').val()) === '' ) {
		
			$('#txt_postCode').parent('div').children('label').children('span').show();
			//$('#txt_postCode').focus();
			__this.valid = false;
		}
		
		if ( $.trim($('#txt_city').val()) === '' ) {
		
			$('#txt_city').parent('div').children('label').children('span').show();
			//$('#txt_city').focus();
			__this.valid = false;
		}
		
		var isChecked = $('#chbxAGB').is(':checked');
		if (isChecked === false) {
		
			$('#chbxAGB').parent('p').parent('div').children('span').show();
			__this.valid = false;
		}
	}
	
	this.createForm = function(){
		
		var str='';
		
		
		
		if ($('.basket_item').length > 0) {
			
			$('#booking').hide('slow');
			$('.basket_remove_item').hide('slow');
			$('#basket_to_book').hide('slow');
			
			slideshow.hide();
		
			
			$('.basket_item').each(function(){
				
				str+='<input type="hidden" name="basketID[]" value="'+$(this).attr('data-basket_id')+'">';
				str+='<input type="hidden" name="adultPassengers_'+$(this).attr('data-basket_id')+'" value="'+$(this).attr('data-adults')+'">';
				str+='<input type="hidden" name="childPassengers_'+$(this).attr('data-basket_id')+'" value="'+$(this).attr('data-children')+'">';
				str+='<input type="hidden" name="routeStationDetailsID_'+$(this).attr('data-basket_id')+'" value="'+$(this).attr('data-id')+'">';
				str+='<input type="hidden" name="price_'+$(this).attr('data-basket_id')+'" value="'+$(this).attr('data-price')+'">';
				str+='<input type="hidden" name="priceTotal_'+$(this).attr('data-basket_id')+'" value="'+$(this).attr('data-price_total')+'">';
				
				
				
				str+='<h1>Fahrgäste:</h1>'+$(this).attr('data-stations')+','+$(this).attr('data-departure');
				
				for (var i=0;i<$(this).attr('data-adults');i++) {
				
					str+='<div id="passengerAdultData_'+$(this).attr('data-basket_id')+'" class="passengerData">';
							 str+='<div>';
								str+='<label>Vorname<span class="form_msg"> Bitte geben Sie den/die Vornamen des/der erwachsenen Passagier(e) an.</span></label>';
								str+='<br>';
								str+='<input type="text" maxlength="20" class="names" name="adultName_'+$(this).attr('data-basket_id')+'[]" value="" autocomplete="off">';
							 str+='</div>';
							 str+='<div>';
								str+='<label>Nachname<span class="form_msg"> Bitte geben Sie den/die Nachnamen des/der erwachsenen Passagier(e) an.</span></label>';
								str+='<br>';
								str+='<input type="text" maxlength="20" class="names" name="adultSurname_'+$(this).attr('data-basket_id')+'[]" value="" autocomplete="off">';
							 str+='</div>';
							 str+='<div>';
								str+='<label>Mobilnummer<span class="form_msg"> Bitte geben Sie eine gültige Telefonnummer an. Diese muss numerisch sein.</span></label>';
								str+='<br>';
								str+='<input type="text" maxlength="20" class="adultTel" name="adultTel_'+$(this).attr('data-basket_id')+'[]" value="" autocomplete="off">';
							 str+='</div>';
						str+='</div>';
				}
				
				for (var i=0;i<$(this).attr('data-children');i++) {
				
					str+='<div id="passengerChildrenData_'+$(this).attr('data-basket_id')+'" class="passengerData">';
							 str+='<div>';
								str+='<label>Vorname(Kind)<span class="form_msg"> Bitte geben Sie den/die Vornamen des/der jungen Passagier(e) an.</span></label>';
								str+='<br>';
								str+='<input type="text" maxlength="20" name="kidName_'+$(this).attr('data-basket_id')+'[]" class="names" value="" autocomplete="off">';
							 str+='</div>';
							 str+='<div>';
								str+='<label>Nachname(Kind)<span class="form_msg"> Bitte geben Sie den/die Nachnamen des/der jungen Passagier(e) an.</span></label>';
								str+='<br>';
								str+='<input type="text" maxlength="20" name="kidSurname_'+$(this).attr('data-basket_id')+'[]" class="names" value="" autocomplete="off">';
							 str+='</div>';
							 
						str+='</div>';
				}
				
			})
			
		
			str+='<hr>';
			
			str+='<h1>Kundendaten</h1>';
					str+='<div id="clientData">';
						str+='<div id="radio">';
							str+='<label>Anrede</label><br>';
							str+='<input type="radio" name="sex" value="Frau" id="txt_frau" class="input_radio" checked><span>Frau</span>';
							str+='<input type="radio" name="sex" value="Herr" id="txt_herr" class="input_radio"><span>Herr</span>';	
						str+='</div>';
						str+='<div>';
							str+='<label>Form</label><br>';
							str+='<input type="radio" name="form" value="Person" id="txt_priv" class="input_radio" checked><span>Person</span>';
							str+='<input type="radio" name="form" value="Firma" id="txt_comp" class="input_radio"><span>Firma</span>';					
						str+='</div><br>';
						str+='<div>';
							str+='<label>Vorname<span class="form_msg"> Bitte geben Sie den Vornamen des Buchungskunden an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="name" id="txt_name" value="" autocomplete="off">';
						str+='</div>';
						str+='<div>';
							str+='<label>Nachname<span class="form_msg"> Bitte geben Sie den Nachnamen des Buchungskunden an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="surname" id="txt_surname" value="" autocomplete="off">';
						str+='</div>';
						str+='<div>';
							str+='<label>Strasse<span class="form_msg"> Bitte geben Sie einen gültigen Strassennamen an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="street" id="txt_street" value="" autocomplete="off">';
						str+='</div>';
						str+='<div>';							
							str+='<label>Hausnummer<span class="form_msg"> Bitte geben Sie eine gültige Hausnummer an.</span></label><br>';
						    str+='<input type="text" maxlength="20" name="streetNumber" class="slim-field" id="txt_streetNumber" value="" autocomplete="off">';
						str+='</div><br>';
						str+='<div>';
						    str+='<label>PLZ<span class="form_msg"> Bitte geben Sie eine gültige Postleitzahl an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="postCode" class="slim-field" id="txt_postCode" value="" autocomplete="off">';
						str+='</div>';
						str+='<div>';
							str+='<label>Stadt<span class="form_msg"> Bitte geben Sie den Wohnort des Buchungskunden an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="city" id="txt_city" value="" autocomplete="off">';
						str+='</div><br>';
						str+='<div>';
							str+='<label>Telefon<span class="form_msg"> Bitte geben Sie eine gültige Telefonnummer an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="mobileNumber" id="txt_mobileNumber" value="" autocomplete="off">';
						str+='</div>';
						str+='<div>';
							str+='<label>E-Mail<span class="form_msg"> Bitte geben Sie eine gültige E-Mail-Adresse an.</span></label><br>';
							str+='<input type="text" maxlength="20" name="mail" id="txt_mail" value="" autocomplete="off">';
						str+='</div>';
						str+='<div id="check"><span class="form_msg">Sie müssen die AGB akzeptieren, um den Buchungsvorgang abschliessen zu können.</span>';
							str+='<p><input type="checkbox" name="chbxAGB" id="chbxAGB" class="checkbox">';	
							str+=' Ich habe die <a href="AGB.html" target="_blank">Allgemeinen Geschäftsbedingungen (AGB )</a>';
							str+='und die <a href=" Datenschutz.html" target="_blank">Datenschutzerklärung</a><br>'; 												
							str+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; gelesen und akzeptiere sie. Mit der Übermittlung der für '; 
							str+='die Abwicklung meiner Buchung<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; notwendigen Daten bin ich ';
							str+='einverstanden.</p>';
							str+='<!--p><input type="checkbox" name="chbx2" class="checkbox">';	
							str+='Ich möchte regelmäßig via E-Mail über neue Linien, Aktionen oder Sonderpreise von<br>';
							str+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;flowerpower ibus informiert werden.</p-->';
						str+='</div>';
						str+='<div id="bookingFormSubmit">';
							str+='<input type="submit" value="VERBINDLICH BUCHEN">';
						str+='</div>';
					str+='<hr>';
					str+='</div>';
					
			
			
			$('#customerBookingForm').empty(); 
			$('#customerBookingForm').append(str); 
			$('#bookingClients').show();
			$('#dont-forget').show();
			$('#stations').hide();
			
			
			$('#customerBookingForm').submit(function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				
				var checkNewbookingForm = new __checkNewbookingForm();
				
				if (checkNewbookingForm.valid === true) {
					
					//$("#customerBookingForm input[type=submit]").attr("disabled", "disabled");
					__this.hide();
					$data = $(this).serialize();
					preload_makeBooking($data);
					//console.log($data);
					
				}
			
			})
		
		}
		
	}
	

}