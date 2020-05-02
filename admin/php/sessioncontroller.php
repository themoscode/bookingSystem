<?php
include_once('sessionconfig.php');

if ( ! $_SESSION["admin_user"]) {
	header("location: http://{$_SERVER["HTTP_HOST"]}/myweb/flowerpower/admin/login.php");
	die;
}

switch ($_REQUEST["action"]) {
	
	case "logout":

		unset ($_SESSION["admin_user"]);

		reload_page();

	break;
		default:
		
};