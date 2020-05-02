<?php 
session_start();

require('class_global.php');
require('class_basket.php');

if (isset($_GET['action'])) {
	
	$B = new __basket();
	$G = new __Global();
	
	switch ($_GET['action']) {
        
		
		case 'addItem':
            echo json_encode($B->addItem($_POST,$G));
            break;
			
		case 'removeItem':
            echo json_encode($B->removeItem($_POST,$G));
            break;
			
		case 'show':
            echo json_encode($B->show($G));
            break;
			
		case 'clear':
            echo json_encode($B->clear($G));
            break;
			
		case 'clearRemains':
            echo json_encode($B->clearRemains($G));
            break;
	
		
		default:
            break;
    }  
} 



?>