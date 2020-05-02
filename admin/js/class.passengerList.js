function __passengerList(){

	var __this = this;

	this.loadEvents = function(){
		
		$('#passengerListHeader').click(function(){
			setField('passengerList');$('#homePage').hide();
		});
		
	}
	
	
	
	this.getPassengerList_pdf = function($obj) {
		
		window.location='php/passengerlist.php?action=getPassengerList_pdf&id='+$obj.attr('data-id')+'&stations='+$obj.attr('data-stations')+'&dateDeparture='+$obj.attr('data-datedeparture')+'&timeDeparture='+$obj.attr('data-timedeparture')+'&dateArrival='+$obj.attr('data-datearrival')+'&timeArrival='+$obj.attr('data-timearrival');
	
	}
	

	
	function searchStationsTo(){
	
		$('#searchStationFromPL').on('change', function() {
			//alert( $(this).val() ); // or $(this).val()
			$data = 'stationFromID='+$(this).val();
			$.post('php/passengerlist.php?action=getStationsTo', $data , function(result) {
				
				$('#searchStationToPL').html(result);
				
				
			},'json');
		});
		
	}
	
	function showDatesInit(){
	
		$('#searchDateFromPP').val(take_date_today());
		$('#searchDateToPP').val(take_date_today('+1 year later'));
		
	
	}
	
	function searchSubmit(){
	
		$('#passengerSearchForm').submit(function(e) { //Formular wird abgesendet
			
			e.preventDefault(); //Aktion submit aufgehalten...
			
			var obj_from = document.getElementById('searchDateFromPP');
			var obj_to = document.getElementById('searchDateToPP');
			
			if (isValidDate(obj_from) === false || isValidDate(obj_to) === false) {
			
				alert("Bitte geben Sie ein gültiges Zeitformat an:[dd-mm-yyyy]"); 
				return;
			
			}
			
			$data = $(this).serialize();
			$.post('php/passengerlist.php?action=getRides', $data , function(result) {
				
				$('#passengerRides').html(result);
				//
				$('.passengerRides, .passengerRides_cancelled').each(function() {
					$(this).click(function(){
					
						//alert('');
						var elmid = $(this).attr('data-elmid');
						setField(elmid);
						
					})
			
				});
				//
				$('.passList_pdf_but').each(function() {
					$(this).click(function(){
						__this.getPassengerList_pdf($(this));
					})
			
				});
				//
				
			},'json');
		
		})
	
	}
	
	this.getSearchHeader = function() {
	
	
		//$data = $(this).serialize();
		$data = '';
		$.post('php/passengerlist.php?action=getSearchHeader', $data , function(result) {
			
			$('#passengerList').html(result.answer);
			//
			showDatesInit();
			searchStationsTo();
			showDatePickerForClassName('dateField_passenger');
			searchSubmit();
			//
			
		},'json');
	
	}
	
	this.initResults = function () {
	
		$('.passengerRides').hide();
		$('.passengerRides_cancelled').hide();
		$('.passListWrapper').hide();
	
	}
	
	
	

}