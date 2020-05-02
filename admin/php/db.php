<?php

	$myPDO = new PDO(
    	'mysql:host=localhost;dbname=flowerpower',
    	"root",
    	"",
    
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$myPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
?>