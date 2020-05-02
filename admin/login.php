<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>FLOWERPOWER i BUS | Administrator-Login</title>
		<link rel="stylesheet" type="text/css" href="css/login.css">
	</head>
	
	<body id="loginpage">
	
		<div id="wrapper">
		
			<p><img src="../images/_logo.png"></img></p>
			<div><?php 
			if (isset($_GET["msg"])) { echo $_GET["msg"]; }
			?></div>
			
			<form action="php/login-check.php" method="post">
				<input type="text" name="username" id="username" placeholder="Benutzername">
				<input type="password" name="password" placeholder="Passwort"><br>
				<input type="submit" id="submit" value="Login">
			</form>
		
		</div>
		<script>document.getElementById('username').focus();</script>
	</body>
</html>