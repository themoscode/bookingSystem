<?php 

session_start();

require ('class_global.php');
require ('class_booking.php');
require ('mPDF/mpdf.php');


if (isset($_GET['action'])) {
	
	$booking = new __booking();
	$G = new __Global();
	$mpdf = new mPDF('c');
	
	switch ($_GET['action']) {
        
		
		case 'makeBookings':
            echo json_encode($booking->makeBookings($_POST,$G));
            break;
				
		default:
            break;
    }  
} 

?>