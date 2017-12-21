<?php
session_start();
unset($_SESSION["authToken"]);
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="ISO-8859-1">
		<title>Secure App : Login </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System</h1>
		<h2>Please enter your details below to Login</h2>
		<?php
			if(isset($_SESSION["error"])){
				if(isset($_SESSION["uName"])){
					$error = $_SESSION["error"];
					$user = $_SESSION["uName"];
					echo "<h3>Account:$user<br> $error</h3>";
				}else{
					$error = $_SESSION["error"];
					echo "<h3>$error</h3>";
				}				
			}
		?>  
		
		<form action="LoginCheck.php" method="POST">
			<label><b>Username</b></label>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
	
			<input type="submit" name="submit" value="Submit">
			<a href = "/Register.php">
			<input type="button" value="Register">
			</a>
		</form>
	</body>
</html>

<?php
    unset($_SESSION["error"]);
?>