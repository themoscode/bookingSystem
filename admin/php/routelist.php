<?php
require ('db.php');
$ergebnis = array(
"message" => "message",
"result" => "result"
);
require ('routelist.sql.php');
echo json_encode($ergebnis);	
?>