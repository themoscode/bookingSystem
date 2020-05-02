<?php 
session_start();

require ('class_global.php');
require ('class_session.php');


if (isset($_GET['action'])) {
	
	$S = new __session();
	$Global = new __Global();
	
	switch ($_GET['action']) {
        
		
		case 'check':
            echo json_encode($S->check($Global));
            break;
			
		case 'init':
            echo json_encode($S->init());
            break;
			
		default:
            break;
    }  
} 


?>