function __basket(){
	
	var __this = this;
	var basket_container = $('#sidebar-basket');
	var book_info = $('#sidebar-book-box');
	
	function calculate_sum(){
		
		var sum = 0;
		
		//alert($('.price_total_adults').length);
	
		if ($('.price_total_adults').length > 0){
			
			$('.price_total_adults').each(function(i, obj){
			
				sum = sum + eval($(this).attr('data-price'));
				//alert($(this).attr('data-price'));
			})
		
		}
		
		if ($('.price_total_children').length > 0){
			
			$('.price_total_children').each(function(i, obj){
			
				sum = sum + eval($(this).attr('data-price'));
				//alert($(this).attr('data-price'));
			})
		
		}
		
		$('#basket_sum_num').html('Summe: '+sum+' €');
		
	}
	
	this.clearRemains = function(){
		
		var $data = '';
		
		$.post('php/basket.php?action=clearRemains', $data , function(result) {
			
			//console.log (result.status);
			__this.show('session_check');//when page loads
			
			
				
		},'json');
	
	}

	
	this.init = function(){
		
		//__this.hide();
		
	}
	
	this.hide = function(){
		basket_container.hide();
		book_info.show();
	}
	
	function basketFunctions(){
	
			$('.basket_remove_item').click(function(){
				
				__this.removeItem($(this).attr('data-id'),$(this).attr('data-basket_id'));
				var item_to_change = $('#btn_addToBasket_'+$(this).attr('data-id'));
				item_to_change.val('Buchen');
				item_to_change.css("background-color", "#93C551");

			})	
			
			$('#basket_to_book').click(function(){
				booking.createForm();
				$('#book_info_text').hide();
				//session.stop();
				session.init();
			})
	
	
	}
	
	
	this.show = function(){
	
		var $data = '';
		var argumentsLength = arguments.length;
		
		$.post('php/basket.php?action=show', $data , function(result) {
			
			if (result.basket!=='') {
				
				basket_container.html(result.basket);
				basketFunctions();
				book_info.hide();		
				calculate_sum();
				$("html, body").animate({ scrollTop: 0 }, "slow" , function(){
					basket_container.show('slow');
				});
				//search.bookButtonsValue();
				
				if (argumentsLength > 0){ //check session when the page first Loads after clearRemains
					
					session.check();
				}
				else {
					session.init();
					
				}
				
			}
			else {
				//search.bookButtonsValueInit();
				basket_container.html('');
				basket_container.hide('slow');
				
				book_info.show();	
				session.stop();
			}
			
			search.postDataMem();//refresh search results
			
		},'json');
		
	
	}
	
	this.clear = function() {
	
		var $data = '';
		$.post('php/basket.php?action=clear', $data , function(result) {
					
			//console.log(result);
			__this.show();
			
		},'json');
	
	}
	
	function book_text_and_buttons(){
	
		slideshow.hide();
		$('#nav li:nth-child(1)').removeClass('selected');
		$('#nav li:nth-child(2)').addClass('selected');
		$('#book_info_text').show();
	
	}
	
	
	this.removeItem = function(routeStationDetailsID,basket_id) {
	
		
		//session.init();
		var $data = 'routeStationDetailsID='+routeStationDetailsID+'&ID='+basket_id;
		$.post('php/basket.php?action=removeItem', $data , function(result) {

			 //console.log(result.status);
			__this.show();
					
		},'json');
	}
	
	
	//date,stationFrom,stationTo,numAdults,numChildren
	this.addItem = function(routeStationDetailsID,adults,children,price_total_adults,price_total_children,stations,departure){
		
		book_text_and_buttons();
		
		//session.init();
	//	console.log(arguments);
		//console.log($.param( arguments ));
		
		var $data = $.param(arguments);
		
		$.post('php/basket.php?action=addItem', $data , function(result) {
					
					/*
					0=201&
					1=1&
					2=0&
					3=35&
					4=0&
					5=Wiesbaden+Hbf+%E2%86%92+Berlin+ZOB&
					6=Di.15.Juli%2C12%3A00
					*/
					//console.log(result.status);
					__this.show();
					
					//$('#nav li:nth-child(1)').removeClass('selected');
					//$('#nav li:nth-child(2)').addClass('selected');
					
					
				},'json');
		
		
	}
	
	

}