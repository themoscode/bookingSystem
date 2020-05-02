<?php

$DBHost = 'localhost';
$DBUser = 'root';
$DBPass = '';
$DBName = 'flowerpower';


$CONFIG = [
    'host' => $DBHost,
    'db' => $DBName,
    'user' => $DBUser,
    'password' => $DBPass
];

$con = mysqli_connect ($DBHost, $DBUser, $DBPass);
////////////////////////////////////

?>