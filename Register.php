<?php
session_start();
unset($_SESSION["authToken"]);
unset($_SESSION["uName"]);
?>


<!DOCTYPE html>
<html>
	<head>
		<script>
		function validatePassword(){
			var regexCheck = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["regForm"]["password"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
		<meta charset="ISO-8859-1">
		<title>Secure App : Register</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<h1>Secure App System</h1>
		<h2>Please enter your details below to Register</h2>
		<?php
			if(isset($_SESSION["uName"])){
				$user = $_SESSION["uName"];
				echo "<h3>Account: $user</h3>";
			}
		
			if(isset($_SESSION["error"])){
				$error = $_SESSION["error"];
				echo "<h3>$error</h3>";
				
			}
		?>  
		<form name= "regForm" action="RegisterCheck.php" method="POST">
			<label><b>Username</b></label><br>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label><br>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
	
			<input type="submit" name="submit" value="Submit" onclick="return validatePassword()">
			<a href = "/Index.php">
			<input type="button" value="Login">
			</a>
		</form>
	</body>
</html>

<?php
	unset($_SESSION["uName"]);
    unset($_SESSION["error"]);
?>