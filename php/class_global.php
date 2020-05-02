<?php 
class __Global{
	
	public function folder_pdf($type) {
	
		if ($type == 'passengerList') {
			return 'X&E_5e3Ew{ba+tH';
		}
		if ($type == 'ticket') {
			return 'zJJN%{wAGb5#UW';
		}
	
	}
	
	public function price($passengerType,$price){
	
		if ($passengerType == 'adult') {
			return $price;
		}
		
		if ($passengerType == 'child') {
			return ($price*0.8);
		}
	
	}
	
	public function dbOpen(){
		require ('db-config.php');
		$myPDO = new PDO("mysql:host=$DBHost;dbname=$DBName","$DBUser","$DBPass",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$myPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
		return $myPDO;
		}
		
	public function clearBasketRemainsTime(){
	
			$result = 15;//min
			return $result;
	
	}
	
	public function endSessionTime(){
	
			$result = 900; //sec = 15 min
			return $result;
	
	}
	
	public function twoDigits($str,$delimeter) {

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
	
	public function getDayName($date){
		
		$tag[1] = "Montag";
		$tag[2] = "Dienstag";
		$tag[3] = "Mittwoch";
		$tag[4] = "Donnerstag";
		$tag[5] = "Freitag";
		$tag[6] = "Samstag";
		$tag[7] = "Sonntag";

		$daynum =  date('N', strtotime($date));
		$result = $tag[$daynum];
		return $result;

	}
	
	public function getMonthName($date){
		
		
		$month['01'] = "Januar";
		$month['02'] = "Februar";
		$month['03'] = "März";
		$month['04'] = "April";
		$month['05'] = "Mai";
		$month['06'] = "Juni";
		$month['07'] = "Juli";
		$month['08'] = "August";
		$month['09'] = "September";
		$month['10'] = "Oktober";
		$month['11'] = "November";
		$month['12'] = "Dezember";
		
		$dateArray = explode (".", $date); 

		$result = $month[$dateArray[1]];

		return $result;
	
	}
	
	public function day_from_dmy($str){

		$dateArray = explode (".", $str);
		$result = $dateArray[0];
		return $result;
	}
	
	public function month_from_dmy($str){

		$dateArray = explode (".", $str);
		$result = $dateArray[1];
		return $result;

	}
	
	
	public function year_from_dmy($str){

		$dateArray = explode (".", $str);
		$result = $dateArray[2];
		return $result;

	}
	
	
	public function dmy_To_ymd($str) {
		
		//echo "str=".$str;

		$dateArray = explode (".", $str); 

	//	echo "dateArray=";
	//	print_r($dateArray);

		$result = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];

		return $result;
	}
	
	
	

}
?>