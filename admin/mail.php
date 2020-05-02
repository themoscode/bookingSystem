<?php

	function spamcheck($field) {
	
	  	// Sanitize e-mail address
  		$field=filter_var($field, FILTER_SANITIZE_EMAIL);
  		
  		// Validate e-mail address
  		if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
    		return TRUE;
  		} 
  		else {
    		return FALSE;
  		}
	}
  
	$from="themos.kost@yahoo.gr";
	$to="themos.kost@yahoo.gr";
	$subject = "mail subject";
	$message = "<b>hello</b>this is the message";

	$mailcheck = spamcheck($from);

	if ($mailcheck==FALSE) {
  		echo "Invalid input";
	} 
	else {
 		$headers = "MIME-Version: 1.0" . "\r\n";
 		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
 		$headers .= "From: $from" . "\r\n";
 
  // message lines should not exceed 70 characters (PHP rule), so wrap it
  //$message = wordwrap($message, 70);
  // send mail
 		mail($to,$subject,$message,$headers);
  		echo "Vielen Dank für Ihr Feedback!";
	}
?>
</body>
</html>