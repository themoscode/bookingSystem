<?php
require ('class_global.php');
require ('class_search.php');

if (isset($_GET['action'])) {
	
	$G = new __Global();
	$S = new __search();
	
	
	
	switch ($_GET['action']) {
        
		
		case 'getResult':
            echo json_encode($S->getResults($_POST,$G));
            break;
	
		
		default:
            break;
    }  
} 


?>