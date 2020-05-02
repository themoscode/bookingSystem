<?php 
$home_class = 'selected';
$booking_class = '';
$book_text_style = ' style="display:none;" ';
$datepicker_class='';
$_SLIDESTATUS = "";


if (isset($_GET['page'])) {

	if ($_GET['page'] == 'buchung') {
		$home_class = '';
		$booking_class = 'selected';
		$book_text_style = ' style="display:block;" ';
		$_SLIDESTATUS = "off";
		$datepicker_class='<link id="fix_datepicker" rel="stylesheet" type="text/css" href="css/fix_datepicker.css">';
	}
	
}

$book_text='<div id="book_info_text" '.$book_text_style .'><h1>Buchungs- und Reservierungsmöglichkeiten</h1>
				Als Alternative zu anderen Wettbewerbern befreien wir Euch von Registrierungen, 
				Bestätigungsvorschriften, Fristen und Bonitätsprüfungen.
				<br><br>
				Anfragen und Buchungen zu Fahrten können schnell und einfach vorgenommen bzw. geklärt werden.
                <br><br></div>';

echo $datepicker_class;
echo '<script> _SLIDESTATUS = "'.$_SLIDESTATUS .'"; </script>';
?>