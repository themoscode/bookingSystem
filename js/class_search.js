function __search(){

	var __this = this;
	
	this.hide = function(){
		
		$('#booking').hide();
	
	}
	
	this.show = function(){
	
		$('#booking').show();
		
	}
	
	this.postDataArr = new Array();
	this.postDataArr['searchDone'] = false;
	
	this.setPostDataArr = function($data,from_station,to_station,chk){
	
		__this.postDataArr['$data'] = $data;
		__this.postDataArr['from_station'] = from_station;
		__this.postDataArr['to_station'] = to_station;
		__this.postDataArr['chk'] = chk;
		__this.postDataArr['searchDone'] = true;
	
	}
	
	function initResult(){
	
		//$("#booking_button").hide();
		$('#to-search-result-area').hide();
		$('#back-search-result-area').hide();
		
		//$('#to-search-result-area h2').html('');
		//$('#resultDatesTo').html('');
		//$('#to-search-results').html('');
		
		//$('#back-search-result-area h2').html('');
		//$('#resultDatesBack').html('');
		//$('#back-search-results').html('');
		
	}
	
	function click_resultDates($obj,direction,chk,from_station,to_station){
	
	var val_from_date = '';
	var val_back_date = '';
	var from_station_str = '';
	var to_station_str = '';
	
	from_station_str = from_station;
	to_station_str = to_station;
	
	
	if (direction === 'to') {
		
		if ($obj.attr('id') === 'nextTo'){
			
			val_from_date = $('#nextDateTo').html().split(',')[1].trim();
			
		}
		else if ($obj.attr('id') === 'previousTo'){
		
			val_from_date = $('#previousDateTo').html().split(',')[1].trim();
			
		}
		else {
			val_from_date = $obj.html().split(',')[1].trim();
		}
		
		if ($('#currentDateBack').length > 0){
			val_back_date = $('#currentDateBack').html().split(',')[1].trim();
		}
		else {
			val_back_date = $('#currentDateTo').html().split(',')[1].trim();
		}
		
		
	}
	
	else {
		
		if ($obj.attr('id') === 'nextBack'){
		
			val_back_date = $('#nextDateBack').html().split(',')[1].trim();
			
		}
		else if ($obj.attr('id') === 'previousBack'){
		
			val_back_date = $('#previousDateBack').html().split(',')[1].trim();
			
		}
		else {
			
			val_back_date = $obj.html().split(',')[1].trim();
		}
		
		val_from_date = $('#resultDatesTo a.resultDatesActive').html().split(',')[1].trim();
		
	}
	
	
	var val_from_city_search = $('#from-city-search').val().trim();
	var val_to_city_search = $('#to-city-search').val().trim();
	var val_adults = $('#adults').val();
	var val_children = $('#children').val();
	
	var str = '';
	
	str +='val_from-city-search='+val_from_city_search;
	str +='&val_from-date='+val_from_date;
	str +='&val_to-city-search='+val_to_city_search;
	
	if (chk.checked == true && val_back_date !== '') {str +='&val_checkbox=on&val_back-date='+val_back_date;}
		
	str +='&val_adults='+val_adults;
	str +='&val_children='+val_children;
	
	preload_postData(str,from_station_str,to_station_str,chk);

	}	
	
	function checkCheckbox(chk,backDate) {

		if (chk.checked == false) {
			backDate.disabled = true;
			backDate.style.opacity = 0.5;
			
			
		}
		else {
			backDate.disabled = false;
			backDate.style.opacity = 1;
		}


	}
	
	function dateButtonsFunctionality(from_station,to_station,chk){
	
		///////////////////////DATES////////////////////////
		$('.resultDates a').mouseover(function(){
			
			if ($(this).attr('class')!= 'resultDatesActive') {
				$(this).attr('class','resultDatesHover');
			}
		})
		
		$('.resultDates a').mouseout(function(){
			
			if ($(this).attr('class')!= 'resultDatesActive') {
				$(this).attr('class','resultDatesInactive');
			}
		})
		
		//click
		$('#resultDatesTo a').click(function(e){
			
			e.preventDefault(); 
			click_resultDates($(this),'to',chk,from_station,to_station);
			
		})
		$('#resultDatesBack a').click(function(e){
			
			e.preventDefault(); 
			click_resultDates($(this),'back',chk,from_station,to_station);
			
		})
		///////////////////////DATES////////////////////////
	
	}
	
	this.bookButtonsValueInit = function(){
		
		$('.btn_addToBasket').val('Buchen').css("background-color", "#93C551");
		
	}
	
	this.bookButtonsValue = function() {
		
			$('.basket_remove_item').each(function(i, obj) {
				var id = $(this).attr('data-id');
				$('#btn_addToBasket_'+id).val('Entfernen');
				$('#btn_addToBasket_'+id).css("background-color", "#F0A71A");
			
		});

	}
	
	
	function searchToBasket(){
		
		$('.btn_addToBasket').click(function(){
			
			if ($('#fix_datepicker').length === 0) {
				$('head').append('<link id="fix_datepicker" rel="stylesheet" type="text/css" href="css/fix_datepicker.css">');
			}
			
			if ($(this).val()==='Buchen'){
			
				//$(this).val('Entfernen');
				//$(this).css("background-color", "#F0A71A");
				basket.addItem($(this).attr('data-id'),$(this).attr('data-adults'),$(this).attr('data-children'),$(this).attr('data-price_total_adults'),$(this).attr('data-price_total_children'),$(this).attr('data-stations'),$(this).attr('data-departure'));
				
			}
			else {
				//$(this).val('Buchen');
				//$(this).css("background-color", "#93C551");
				
				var basket_id = $('#basket_item_'+$(this).attr('data-id')).attr('data-basket_id');
				
				basket.removeItem($(this).attr('data-id'),basket_id);
				
			}
			//basket.show();
			
		})
		
			
	}
	
	this.postDataMem = function(){
		
		if  (__this.postDataArr['searchDone'] === true) {
			
			var $data = __this.postDataArr['$data'];
			var from_station = __this.postDataArr['from_station'];
			var to_station = __this.postDataArr['to_station'];
			var chk = __this.postDataArr['chk'];
			preload_postData($data,from_station,to_station,chk);
		}
		
	
	}
	
	
	function postData($data,from_station,to_station,chk){
		
		__this.setPostDataArr($data,from_station,to_station,chk);
		
		
		$.post('php/search.php?action=getResult', $data , function(result) {
					
					var stations = from_station+' → '+to_station;
					var stations_back = to_station+' → '+from_station;
					
					var msg = 'An diesem Tag gibt es leider keine Fahrten.';
					if (result.validStations === false) {
						msg='Zur Zeit keine Fahrten auf diesen Linien';
						stations = '';
						stations_back = '';
					}
					var str_notFound = '<br><div class="no-ride-message" ><span><img src="images/station.png"><br>'+msg+'</span><br></div>';
					
					var str_resultRidesTo = str_notFound;
					var str_resultRidesBack = str_notFound;
					
					if (result.resultRidesTo !=='') {
						str_resultRidesTo = result.resultRidesTo;
					}
					if (result.resultRidesBack !=='') {
						str_resultRidesBack = result.resultRidesBack;
					}
						
						var to_search_result_area_html= '<h2></h2>';
						to_search_result_area_html+='<ul class="resultDates" id="resultDatesTo"></ul>';
						to_search_result_area_html+='<div id="to-search-results" class="searchResults"></div>';	
						$('#to-search-result-area').html(to_search_result_area_html);
					
						$('#to-search-result-area h2').html(stations);
						$('#resultDatesTo').html(result.resultDatesTo);
						$('#to-search-results').html(str_resultRidesTo);
						$('#to-search-result-area').show();
					
					if ($('#checkbox').prop('checked') === true && $("#back-date").val() !== '') {
						
						var back_search_result_area_html= '<h2></h2>';
						back_search_result_area_html+='<ul class="resultDates" id="resultDatesBack"></ul>';
						back_search_result_area_html+='<div id="back-search-results" class="searchResults"></div>';	
						$('#back-search-result-area').html(back_search_result_area_html);
							
						$('#back-search-result-area h2').html(stations_back);
						$('#resultDatesBack').html(result.resultDatesBack);
						$('#back-search-results').html(str_resultRidesBack);
						$('#back-search-result-area').show();
							
							
					
					
					}
					searchToBasket();
					//__this.bookButtonsValueInit();
					__this.bookButtonsValue();
					
					dateButtonsFunctionality(from_station,to_station,chk);
					
					$('#stations').hide();
					
					
				},'json');


	}
	
	function preload_postData($data,from_station,to_station,chk){
	
		$('#to-search-result-area').html('<img src="images/preloader.png" border="0" class="preloader">');
		$('#to-search-result-area').show();
					
					
		if (chk.checked == true) {
		
			$('#back-search-result-area').html('<img src="images/preloader.png" border="0" class="preloader">');
			$('#back-search-result-area').show();
		}
	
		setTimeout(function(){postData($data,from_station,to_station,chk);}, 500);
	}
	
	
	this.init = function(){
		//
		// checkbox
			
			var chk = document.getElementById('checkbox');
			var backDate = document.getElementById('back-date');
			backDate.style.opacity = 0.5;
			backDate.disabled = true;
			chk.onchange=function(){   
			checkCheckbox(chk,backDate);	
			}
			$('#to-search-result-area').hide();
			$('#back-search-result-area').hide();
			
			initResult();
			
			//search form submit
			$('#search-form').submit(function(e) { //Formular wird abgesendet
				
				e.preventDefault(); //Aktion submit aufgehalten...
				
				var from_station = $('#from-city-search').val().trim();
				var to_station = $('#to-city-search').val().trim();
				
				initResult();
				
				if (from_station === '' || to_station === ''){return;}
				
				$data = $(this).serialize();
				
				//slideshow.hide();
				
				///////////////////////
				
				preload_postData($data,from_station,to_station,chk);
				
			});
			//go to booking form
			$('#basket_to_book').click(function(){
				
				booking.createForm();
				session.init();
			})	
			
			
			
			
			
		
	}

}			