<?php
session_start();
if(!isset($_SESSION["authToken"])){
	$authError = "Error in receiving Authorization Token";
	$_SESSION["error"] = $authError;
	header("location: Index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<script>
		function validatePassword(){
			var regexCheck = new pwExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["pwForm"]["newPass"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
		<meta charset="ISO-8859-1">
		<title>Secure App : Password Change</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Password Change</h1>
		<h2>Please enter the your new password below.</h2>
		<?php
			if(isset($_SESSION["error"])){
				$error = $_SESSION["error"];
				echo "<h3>$error</h3><br>$saltedOldP<br>";
			}
		?>  
		<form name= "pwForm" action="PasswordCheck.php" method="POST">
			<label><b>New Password</b></label>
			<input type="password" name="newPass" placeholder="Enter New Password" required><br><br>
	
			<input type="button" value="Submit" onclick="return validatePassword()">
			<a href = "/Welcome.php">
			<input type="button" value="Back">
		</form>
	</body>
</html>

<?php
    unset($_SESSION["error"]);
?>