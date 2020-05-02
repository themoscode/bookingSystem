<?php

//phpinfo();

session_start();

define("TESTMODUS", 1);

	if(TESTMODUS) {
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
	}
	else {
			error_reporting(0);
		  };

		//  echo "HTTP_HOST=".$_SERVER["HTTP_HOST"];
		  

if ($_SERVER["HTTP_HOST"]=="localhost") {

		$DBHost = "localhost";
		$DBUser = "root";
		$DBPass = "";
		$DBName = "flowerpower";
	}
	else { 
		 // MySQL-data for flowerpower-ibus.de
		 $DBHost = "localhost";
		 $DBUser = "...";
		 $DBPass = "...";
		 $DBName = "...";
	};

$con = mysqli_connect ($DBHost, $DBUser, $DBPass);
mysqli_select_db ($con,$DBName) or die("Fehler: " . mysqli_error() );


function reload_page () {
	header("location: http://{$_SERVER["HTTP_HOST"]}/myweb/flowerpower/admin/index.php");	
	die;
}

/*
function error_reporting ($error-report, $SQLString) {
	if( TESTMODUS ) {
		echo "Fehler: " . $error-report . "<br>SQL: " .  $SQLString;
	}
	die;
}
*/