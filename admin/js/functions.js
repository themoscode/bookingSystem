String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};


function getPrice($type,$price){

	if ($type ==='child') {return parseInt(($price*0.8),10);}
	
	return $price;
}

function checkTimeFormat(obj) {
        var isValid = /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])(:[0-5][0-9])?$/.test(obj.value);
		obj.focus(); 
		return isValid;
    }


function isValidDate(obj){ //dd-mm-YYY
  
  var reg = /(0[1-9]|[12][0-9]|3[01])[-.](0[1-9]|1[012])[-.](19|20)\d\d/;
	//alert(obj.value);
	
	var isValid = reg.test(obj.value);
	
	//alert(isValid);
	
	  if (isValid === false) {
		obj.focus();
	  }
  
  return isValid;
  
}


function take_date_today() {

	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
	
	if (arguments.length>0) {// +1 year later
		yyyy=yyyy+1;
	}
	
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    return dd+'-'+mm+'-'+yyyy;
 
}

function today_ymd_hms_ms() {

	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
	
    var yyyy = today.getFullYear();
	
	var hour = today.getHours();if (hour < 10) {hour='0'+hour;}
	var minute = today.getMinutes();if (minute < 10) {minute='0'+minute;}
	var second = today.getSeconds();if (second < 10) {second='0'+second;}
	
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    var curDate = yyyy+'-'+mm+'-'+dd;
	var curTime = hour + ':' + minute + ':'  + second; 
         
    var curDateTimeFull = new Date(curDate+'T'+curTime).getTime(); // milliseconds
    
	curDateTimeFull = parseInt(curDateTimeFull,10)
	
	return curDateTimeFull;
	
 
}



function cloneValue(obj,attribute){

	var val=obj.value;
	var attrValue = obj.getAttribute(attribute);
	
	var G = getElementsByAttribute(attribute);
	
	if (G.length>0) {
	
		for (var i=0;i<G.length;i++) {
		
			if (G[i].getAttribute(attribute) === attrValue ){
				G[i].value = val;
			}
		
	
		}
	
	
	}
	
}




function getElementsByAttribute(attribute){
  var matchingElements = [];
  var allElements = document.getElementsByTagName('*');
  for (var i = 0; i < allElements.length; i++)
  {
    if (allElements[i].getAttribute(attribute))
    {
      // Element exists with attribute. Add to array.
      matchingElements.push(allElements[i]);
    }
  }
  
  //alert(matchingElements[0].getAttribute(attribute));
  
  return matchingElements;
}

function setFieldOn(theField) {
	document.getElementById(theField).className="fieldOn";
	
}

function setFieldOff(theField) {
	document.getElementById(theField).className="fieldOff";
	
}

$('#newBook').slideToggle( "slow" );

function setField(theField){

	$('#'+theField).slideToggle( "fast" );


}

function setField_OLD_BUT_WORKS(theField){

	if (document.getElementById(theField)) {

		var obj=document.getElementById(theField);
		if (obj.className ==="fieldOn"){
			setFieldOff(theField);
		}
		else {
		setFieldOn(theField); 
		}
	}


}


function setFieldForIdandTargetId(id,targetId) {



	var fromObj = document.getElementById(id);
	
	fromObj.onclick = function(){
	
		setField(targetId);
	
	
	}
	
	
}

function cloneValuebyAttributeName(dataFlag,dataUniqueID){ //NOT ON ACTION, FIX?
	
	//onblur=cloneValue(this,"data-datestationfromid'.$routeStationDetail['uniqueRouteID'].'");
	
	var G = getElementsByAttribute(dataFlag);
		
		//alert(dataFlag);
		//alert(G.length);
		
		for (var i=0;i<G.length;i++) {
			
			dataFlagValue = G[i].getAttribute(dataFlag);
			
			//alert(dataFlagValue);
			
			G[i].onblur = function(){
				
				var dataUniqueIDValue = this.getAttribute(dataUniqueID);
				cloneValue(this,''+dataFlagValue+dataUniqueIDValue);
				}
		
		}


}


function setFieldForClassName(className,target) {
	
	
	var G=document.getElementsByClassName(className);
	//alert(G.length);
	
	 for (var i=0; i<G.length; i++) {
		
		
		G[i].onclick = function(){
		//alert(className);
			var dataId = this.getAttribute('data-id');
			//alert(target+'_'+dataId);
			
			if (document.getElementById(target+'_'+dataId)) {
				
				setField(target+'_'+dataId);
				}
		};
	 
	 }

}

function showDatePicker(theTarget){

		
		//console.log($('#'+theTarget).length);
		
		if ($('#'+theTarget).prop('disabled') === true) {return;}
			//console.log('theTarget='+theTarget);
			
		__JsDatePick[theTarget] = new JsDatePick({
				useMode:2,
				target:theTarget,
				//limitToToday:false,
				dateFormat:"%d-%m-%Y"
				
			});
			
		__JsDatePick[theTarget].addOnSelectedDelegate(function(){
				
			var attr = $("#"+theTarget).attr('data-flag');

			if (typeof attr !== typeof undefined && attr !== false) {
					
				var obj = document.getElementById(theTarget);
				var uniquerouteid = $("#"+theTarget).attr('data-uniquerouteid');
				var flag = $("#"+theTarget).attr('data-flag');
				cloneValue(obj,flag+uniquerouteid);
					
			}
		
		});
		
}





function showDatePickerForClassName(className) {
	
	//console.log('className='+className);
	$('.'+className).each(function(i, obj) {
				//console.log($(this).attr('id'));
				var theTarget = $(this).attr('id');
				showDatePicker(theTarget);
		});
	
	
		//console.log('finished');
	
}



