<?php
include_once('sessionconfig.php');

$user = mysqli_real_escape_string( $con,trim($_POST["username"]));
$pass = mysqli_real_escape_string( $con,trim($_POST["password"]));

$sql_user = "
				SELECT 
						id
					   ,username
				FROM 
						admin_user
				WHERE 
						username = '$user'
				AND 
						password = '$pass' 
			";

$result = mysqli_query ($con,$sql_user) or die ("Error: " . mysqli_error());

if( mysqli_num_rows($result)>0 ) { 
	mysqli_fetch_assoc($result);
   
	$_SESSION["admin_user"] = $user;
   
	header("location: http://{$_SERVER["HTTP_HOST"]}/myweb/flowerpower/admin/index.php"); 
}
else { 
	   header("location: http://{$_SERVER["HTTP_HOST"]}/myweb/flowerpower/admin/login.php?msg=Ihr Login-Versuch war nicht erfolgreich.<p>Bitte überprüfen Sie Benutzernamen und Passwort.</p>");
	die;
}
?>